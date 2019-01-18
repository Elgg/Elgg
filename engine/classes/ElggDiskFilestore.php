<?php
/**
 * A filestore that uses disk as storage.
 *
 * @warning This should be used by a wrapper class
 * like {@link \ElggFile}.
 *
 * @package    Elgg.Core
 * @subpackage FileStore.Disk
 */
class ElggDiskFilestore extends \ElggFilestore {
	/**
	 * Directory root.
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
	public function __construct($directory_root = "") {
		if ($directory_root) {
			$this->dir_root = $directory_root;
		} else {
			$this->dir_root = _elgg_config()->dataroot;
		}
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
	 * @throws InvalidParameterException
	 * @return resource File pointer resource
	 */
	public function open(\ElggFile $file, $mode) {
		$fullname = $this->getFilenameOnFilestore($file);

		// Split into path and name
		$ls = strrpos($fullname, "/");
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
				_elgg_services()->logger->warning("Couldn't create directory: $path");
				return false;
			}
		}

		switch ($mode) {
			case "read" :
				$mode = "rb";
				break;
			case "write" :
				$mode = "w+b";
				break;
			case "append" :
				$mode = "a+b";
				break;
			default:
				$msg = "Unrecognized file mode '" . $mode . "'";
				throw new \InvalidParameterException($msg);
		}

		return fopen($fullname, $mode);

	}

	/**
	 * Write data to a file.
	 *
	 * @param resource $f    File pointer resource
	 * @param mixed    $data The data to write.
	 *
	 * @return false|int
	 */
	public function write($f, $data) {
		return fwrite($f, $data);
	}

	/**
	 * Read data from a file.
	 *
	 * @param resource $f      File pointer resource
	 * @param int      $length The number of bytes to read
	 * @param int      $offset The number of bytes to start after
	 *
	 * @return mixed Contents of file or false on fail.
	 */
	public function read($f, $length, $offset = 0) {
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
	public function close($f) {
		return fclose($f);
	}

	/**
	 * Delete an \ElggFile file.
	 *
	 * @param \ElggFile $file            File to delete
	 * @param bool      $follow_symlinks If true, will also delete the target file if the current file is a symlink
	 * @return bool
	 */
	public function delete(\ElggFile $file, $follow_symlinks = true) {
		$filename = $this->getFilenameOnFilestore($file);
		if (file_exists($filename) || is_link($filename)) {
			if ($follow_symlinks && is_link($filename) && file_exists($filename)) {
				$target = readlink($filename);
				file_exists($target) && unlink($target);
			}
			return unlink($filename);
		} else {
			return true;
		}
	}

	/**
	 * Seek to the specified position.
	 *
	 * @param resource $f        File resource
	 * @param int      $position Position in bytes
	 *
	 * @return int 0 for success, or -1
	 */
	public function seek($f, $position) {
		return fseek($f, $position);
	}

	/**
	 * Return the current location of the internal pointer
	 *
	 * @param resource $f File pointer resource
	 *
	 * @return int|false
	 */
	public function tell($f) {
		return ftell($f);
	}

	/**
	 * Tests for end of file on a file pointer
	 *
	 * @param resource $f File pointer resource
	 *
	 * @return bool
	 */
	public function eof($f) {
		return feof($f);
	}

	/**
	 * Returns the file size of an \ElggFile file.
	 *
	 * @param \ElggFile $file File object
	 *
	 * @return int The file size
	 */
	public function getFileSize(\ElggFile $file) {
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
	 * @throws InvalidParameterException
	 */
	public function getFilenameOnFilestore(\ElggFile $file) {
		$owner_guid = $file->getOwnerGuid();
		if (!$owner_guid) {
			$owner_guid = _elgg_services()->session->getLoggedInUserGuid();
		}

		if (!$owner_guid) {
			$msg = "File " . $file->getFilename() . " (file guid:" . $file->guid . ") is missing an owner!";
			throw new \InvalidParameterException($msg);
		}

		$filename = $file->getFilename();
		if (!$filename) {
			return '';
		}

		$dir = new \Elgg\EntityDirLocator($owner_guid);

		return $this->dir_root . $dir . $file->getFilename();
	}

	/**
	 * Returns the contents of the \ElggFile file.
	 *
	 * @param \ElggFile $file File object
	 *
	 * @return string
	 */
	public function grabFile(\ElggFile $file) {
		return file_get_contents($file->getFilenameOnFilestore());
	}

	/**
	 * Tests if an \ElggFile file exists.
	 *
	 * @param \ElggFile $file File object
	 *
	 * @return bool
	 */
	public function exists(\ElggFile $file) {
		if (!$file->getFilename()) {
			return false;
		}
		return file_exists($this->getFilenameOnFilestore($file));
	}

	/**
	 * Returns the size of all data stored under a directory in the disk store.
	 *
	 * @param string $prefix         The prefix to check under.
	 * @param string $container_guid The guid of the entity whose data you want to check.
	 *
	 * @return int|false
	 */
	public function getSize($prefix, $container_guid) {
		if ($container_guid) {
			$dir = new \Elgg\EntityDirLocator($container_guid);
			return get_dir_size($this->dir_root . $dir . $prefix);
		} else {
			return false;
		}
	}

	/**
	 * Create a directory $dirroot
	 *
	 * @param string $dirroot The full path of the directory to create
	 *
	 * @throws IOException
	 * @return true
	 */
	protected function makeDirectoryRoot($dirroot) {
		if (!file_exists($dirroot)) {
			if (!@mkdir($dirroot, 0755, true)) {
				throw new \IOException("Could not make " . $dirroot);
			}
		}

		return true;
	}

	/**
	 * Returns a list of attributes to save to the database when saving
	 * the \ElggFile object using this file store.
	 *
	 * @return array
	 */
	public function getParameters() {
		return ["dir_root" => $this->dir_root];
	}

	/**
	 * Sets parameters that should be saved to database.
	 *
	 * @param array $parameters Set parameters to save to DB for this filestore.
	 *
	 * @return bool
	 */
	public function setParameters(array $parameters) {
		if (isset($parameters['dir_root'])) {
			$this->dir_root = $parameters['dir_root'];
			return true;
		}

		return false;
	}
}
