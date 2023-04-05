<?php

namespace Elgg\Filesystem\Filestore;

use Elgg\Exceptions\DomainException;
use Elgg\Exceptions\Filesystem\IOException;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Filesystem\Filestore;
use Elgg\Project\Paths;

/**
 * A filestore that uses disk as storage.
 *
 * @warning This should be used by a wrapper class
 * like {@link \ElggFile}.
 */
class DiskFilestore extends Filestore {

	/**
	 * @var string Directory root
	 */
	protected $dir_root;

	/**
	 * Number of entries per matrix dir.
	 * You almost certainly don't want to change this.
	 */
	const BUCKET_SIZE = 5000;

	/**
	 * Construct a disk filestore using the given directory root.
	 *
	 * @param string $directory_root Root directory, must end in "/"
	 */
	public function __construct($directory_root = '') {
		if (!$directory_root) {
			$directory_root = _elgg_services()->config->dataroot;
		}
		
		$this->dir_root = Paths::sanitize($directory_root);
	}

	/**
	 * Open a file for reading, writing, or both.
	 *
	 * @note All files are opened binary safe.
	 * @note This will try to create the a directory if it doesn't exist and is opened
	 * in write or append mode.
	 *
	 * @param \ElggFile $file The file to open
	 * @param string    $mode read, write, or append.
	 *
	 * @return false|resource File pointer resource or false on failure
	 * @throws \Elgg\Exceptions\DomainException
	 */
	public function open(\ElggFile $file, string $mode) {
		$fullname = $this->getFilenameOnFilestore($file);

		// Split into path and name
		$ls = strrpos($fullname, '/');
		if ($ls === false) {
			$ls = 0;
		}

		$path = substr($fullname, 0, $ls);

		if (($mode === 'read') && (!file_exists($fullname))) {
			return false;
		}

		// Try to create the dir for valid write modes
		if ($mode == 'write' || $mode == 'append') {
			try {
				$this->makeDirectoryRoot($path);
			} catch (Exception $e) {
				_elgg_services()->logger->warning($e);
				return false;
			}
		}

		switch ($mode) {
			case 'read':
				$mode = 'rb';
				break;
			case 'write':
				$mode = 'w+b';
				break;
			case 'append':
				$mode = 'a+b';
				break;
			default:
				throw new DomainException("Unrecognized file mode '{$mode}'");
		}

		return fopen($fullname, $mode);
	}

	/**
	 * Write data to a file.
	 *
	 * @param resource $f    File pointer resource
	 * @param mixed    $data The data to write.
	 *
	 * @return int
	 */
	public function write($f, $data): int {
		return fwrite($f, $data);
	}

	/**
	 * Read data from a file.
	 *
	 * @param resource $f      File pointer resource
	 * @param int      $length The number of bytes to read
	 * @param int      $offset The number of bytes to start after
	 *
	 * @return string|false Contents of file or false on fail.
	 */
	public function read($f, int $length, int $offset = 0): string|false {
		if ($offset) {
			$this->seek($f, $offset);
		}

		return fread($f, $length);
	}

	/**
	 * Close a file pointer
	 *
	 * @param resource $f A file pointer resource
	 *
	 * @return bool
	 */
	public function close($f): bool {
		return fclose($f);
	}

	/**
	 * Delete an \ElggFile file.
	 *
	 * @param \ElggFile $file            File to delete
	 * @param bool      $follow_symlinks If true, will also delete the target file if the current file is a symlink
	 *
	 * @return bool
	 */
	public function delete(\ElggFile $file, bool $follow_symlinks = true): bool {
		$filename = $this->getFilenameOnFilestore($file);
		if (file_exists($filename) || is_link($filename)) {
			if ($follow_symlinks && is_link($filename) && file_exists($filename)) {
				$target = readlink($filename);
				file_exists($target) && unlink($target);
			}
			
			return unlink($filename);
		}

		return true;
	}

	/**
	 * Seek to the specified position.
	 *
	 * @param resource $f        File resource
	 * @param int      $position Position in bytes
	 *
	 * @return int 0 for success, or -1
	 */
	public function seek($f, int $position): int {
		return fseek($f, $position);
	}

	/**
	 * Return the current location of the internal pointer
	 *
	 * @param resource $f File pointer resource
	 *
	 * @return int|false
	 */
	public function tell($f): int|false {
		return ftell($f);
	}

	/**
	 * Tests for end of file on a file pointer
	 *
	 * @param resource $f File pointer resource
	 *
	 * @return bool
	 */
	public function eof($f): bool {
		return feof($f);
	}

	/**
	 * Returns the file size of an \ElggFile file.
	 *
	 * @param \ElggFile $file File object
	 *
	 * @return int The file size
	 */
	public function getFileSize(\ElggFile $file): int {
		return filesize($this->getFilenameOnFilestore($file));
	}

	/**
	 * Get the filename as saved on disk for an \ElggFile object
	 *
	 * Returns an empty string if no filename set
	 *
	 * @param \ElggFile $file File object
	 *
	 * @return string The full path of where the file is stored
	 * @throws InvalidArgumentException
	 */
	public function getFilenameOnFilestore(\ElggFile $file): string {
		
		$owner_guid = null;
		if (!empty($file->guid) && $file->getSubtype() === 'file') {
			$owner_guid = $file->guid;
		}
	
		if (empty($owner_guid)) {
			$owner_guid = $file->owner_guid ?: _elgg_services()->session_manager->getLoggedInUserGuid();
		}

		if (empty($owner_guid)) {
			throw new InvalidArgumentException("File {$file->getFilename()} (file guid: {$file->guid}) is missing an owner!");
		}

		$filename = $file->getFilename();
		if (!$filename) {
			return '';
		}

		$dir = new \Elgg\EntityDirLocator($owner_guid);

		return Paths::sanitize($this->dir_root . $dir . $file->getFilename(), false);
	}

	/**
	 * Returns the contents of the \ElggFile file.
	 *
	 * @param \ElggFile $file File object
	 *
	 * @return false|string
	 */
	public function grabFile(\ElggFile $file): string|false {
		return file_get_contents($file->getFilenameOnFilestore());
	}

	/**
	 * Tests if an \ElggFile file exists.
	 *
	 * @param \ElggFile $file File object
	 *
	 * @return bool
	 */
	public function exists(\ElggFile $file): bool {
		if (!$file->getFilename()) {
			return false;
		}
		
		try {
			$real_filename = $this->getFilenameOnFilestore($file);
		} catch (InvalidArgumentException $e) {
			// something wrong with the filename
			return false;
		}

		return file_exists($real_filename);
	}

	/**
	 * Create a directory $dirroot
	 *
	 * @param string $dirroot The full path of the directory to create
	 *
	 * @throws \Elgg\Exceptions\Filesystem\IOException
	 * @return void
	 */
	protected function makeDirectoryRoot($dirroot): void {
		if (file_exists($dirroot)) {
			return;
		}
		
		error_clear_last();
		if (!@mkdir($dirroot, 0755, true)) {
			$last_error = error_get_last();
			throw new IOException("Couldn't create directory: {$dirroot}" . $last_error ? ': ' . $last_error['message'] : '');
		}
	}

	/**
	 * Returns a list of attributes to save to the database when saving
	 * the \ElggFile object using this file store.
	 *
	 * @return array
	 */
	public function getParameters(): array {
		return [
			'dir_root' => $this->dir_root,
		];
	}

	/**
	 * Sets parameters that should be saved to database.
	 *
	 * @param array $parameters Set parameters to save to DB for this filestore.
	 *
	 * @return bool
	 */
	public function setParameters(array $parameters): bool {
		if (isset($parameters['dir_root'])) {
			$this->dir_root = Paths::sanitize($parameters['dir_root']);
			return true;
		}

		return false;
	}
}
