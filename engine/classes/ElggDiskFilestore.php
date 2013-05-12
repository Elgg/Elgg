<?php
/**
 * A filestore that uses disk as storage.
 *
 * @warning This should be used by a wrapper class
 * like {@link ElggFile}.
 *
 * @package    Elgg.Core
 * @subpackage FileStore.Disk
 * @link       http://docs.elgg.org/DataModel/FileStore/Disk
 */
class ElggDiskFilestore extends ElggFilestore {
	/**
	 * Directory root.
	 */
	private $dir_root;

	/**
	 * Default depth of file directory matrix
	 */
	private $matrix_depth = 5;

	/**
	 * Construct a disk filestore using the given directory root.
	 *
	 * @param string $directory_root Root directory, must end in "/"
	 */
	public function __construct($directory_root = "") {
		global $CONFIG;

		if ($directory_root) {
			$this->dir_root = $directory_root;
		} else {
			$this->dir_root = $CONFIG->dataroot;
		}
	}

	/**
	 * Open a file for reading, writing, or both.
	 *
	 * @note All files are opened binary safe.
	 * @warning This will try to create the a directory if it doesn't exist,
	 * even in read-only mode.
	 *
	 * @param ElggFile $file The file to open
	 * @param string   $mode read, write, or append.
	 *
	 * @throws InvalidParameterException
	 * @return resource File pointer resource
	 * @todo This really shouldn't try to create directories if not writing.
	 */
	public function open(ElggFile $file, $mode) {
		$fullname = $this->getFilenameOnFilestore($file);

		// Split into path and name
		$ls = strrpos($fullname, "/");
		if ($ls === false) {
			$ls = 0;
		}

		$path = substr($fullname, 0, $ls);
		$name = substr($fullname, $ls);
		// @todo $name is unused, remove it or do we need to fix something?

		// Try and create the directory
		try {
			$this->makeDirectoryRoot($path);
		} catch (Exception $e) {

		}

		if (($mode != 'write') && (!file_exists($fullname))) {
			return false;
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
				$msg = elgg_echo('InvalidParameterException:UnrecognisedFileMode', array($mode));
				throw new InvalidParameterException($msg);
		}

		return fopen($fullname, $mode);

	}

	/**
	 * Write data to a file.
	 *
	 * @param resource $f    File pointer resource
	 * @param mixed    $data The data to write.
	 *
	 * @return bool
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
	 * Delete an ElggFile file.
	 *
	 * @param ElggFile $file File to delete
	 *
	 * @return bool
	 */
	public function delete(ElggFile $file) {
		$filename = $this->getFilenameOnFilestore($file);
		if (file_exists($filename)) {
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
	 * @return bool
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
	 * Returns the file size of an ElggFile file.
	 *
	 * @param ElggFile $file File object
	 *
	 * @return int The file size
	 */
	public function getFileSize(ElggFile $file) {
		return filesize($this->getFilenameOnFilestore($file));
	}

	/**
	 * Get the filename as saved on disk for an ElggFile object
	 *
	 * Returns an empty string if no filename set
	 *
	 * @param ElggFile $file File object
	 *
	 * @return string The full path of where the file is stored
	 * @throws InvalidParameterException
	 */
	public function getFilenameOnFilestore(ElggFile $file) {
		$owner_guid = $file->getOwnerGuid();
		if (!$owner_guid) {
			$owner_guid = elgg_get_logged_in_user_guid();
		}

		if (!$owner_guid) {
			$msg = elgg_echo('InvalidParameterException:MissingOwner',
				array($file->getFilename(), $file->guid));
			throw new InvalidParameterException($msg);
		}

		$filename = $file->getFilename();
		if (!$filename) {
			return '';
		}

		return $this->dir_root . $this->makeFileMatrix($owner_guid) . $filename;
	}

	/**
	 * Returns the contents of the ElggFile file.
	 *
	 * @param ElggFile $file File object
	 *
	 * @return string
	 */
	public function grabFile(ElggFile $file) {
		return file_get_contents($file->getFilenameOnFilestore());
	}

	/**
	 * Tests if an ElggFile file exists.
	 *
	 * @param ElggFile $file File object
	 *
	 * @return bool
	 */
	public function exists(ElggFile $file) {
		if (!$file->getFilename()) {
			return false;
		}
		return file_exists($this->getFilenameOnFilestore($file));
	}

	/**
	 * Returns the size of all data stored under a directory in the disk store.
	 *
	 * @param string $prefix         Optional/ The prefix to check under.
	 * @param string $container_guid The guid of the entity whose data you want to check.
	 *
	 * @return int|false
	 */
	public function getSize($prefix = '', $container_guid) {
		if ($container_guid) {
			return get_dir_size($this->dir_root . $this->makeFileMatrix($container_guid) . $prefix);
		} else {
			return false;
		}
	}

	// @codingStandardsIgnoreStart
	/**
	 * Create a directory $dirroot
	 *
	 * @param string $dirroot The full path of the directory to create
	 *
	 * @throws IOException
	 * @return true
	 * @deprecated 1.8 Use ElggDiskFilestore::makeDirectoryRoot()
	 */
	protected function make_directory_root($dirroot) {
		elgg_deprecated_notice('ElggDiskFilestore::make_directory_root() is deprecated by ::makeDirectoryRoot()', 1.8);

		return $this->makeDirectoryRoot($dirroot);
	}
	// @codingStandardsIgnoreEnd

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
			if (!@mkdir($dirroot, 0700, true)) {
				throw new IOException(elgg_echo('IOException:CouldNotMake', array($dirroot)));
			}
		}

		return true;
	}

	// @codingStandardsIgnoreStart
	/**
	 * Multibyte string tokeniser.
	 *
	 * Splits a string into an array. Will fail safely if mbstring is
	 * not installed.
	 *
	 * @param string $string  String
	 * @param string $charset The charset, defaults to UTF8
	 *
	 * @return array
	 * @deprecated 1.8 Files are stored by date and guid; no need for this.
	 */
	private function mb_str_split($string, $charset = 'UTF8') {
		elgg_deprecated_notice('ElggDiskFilestore::mb_str_split() is deprecated.', 1.8);

		if (is_callable('mb_substr')) {
			$length = mb_strlen($string);
			$array = array();

			while ($length) {
				$array[] = mb_substr($string, 0, 1, $charset);
				$string = mb_substr($string, 1, $length, $charset);

				$length = mb_strlen($string);
			}

			return $array;
		} else {
			return str_split($string);
		}
	}
	// @codingStandardsIgnoreEnd

	// @codingStandardsIgnoreStart
	/**
	 * Construct a file path matrix for an entity.
	 *
	 * @param int $identifier The guide of the entity to store the data under.
	 *
	 * @return string The path where the entity's data will be stored.
	 * @deprecated 1.8 Use ElggDiskFilestore::makeFileMatrix()
	 */
	protected function make_file_matrix($identifier) {
		elgg_deprecated_notice('ElggDiskFilestore::make_file_matrix() is deprecated by ::makeFileMatrix()', 1.8);

		return $this->makeFileMatrix($identifier);
	}
	// @codingStandardsIgnoreEnd

	/**
	 * Construct a file path matrix for an entity.
	 *
	 * @param int $guid The guide of the entity to store the data under.
	 *
	 * @return string The path where the entity's data will be stored.
	 */
	protected function makeFileMatrix($guid) {
		$entity = get_entity($guid);

		if (!($entity instanceof ElggEntity) || !$entity->time_created) {
			return false;
		}

		$time_created = date('Y/m/d', $entity->time_created);

		return "$time_created/$entity->guid/";
	}

	// @codingStandardsIgnoreStart
	/**
	 * Construct a filename matrix.
	 *
	 * Generates a matrix using the entity's creation time and
	 * unique guid.
	 *
	 * File path matrixes are:
	 * YYYY/MM/DD/guid/
	 *
	 * @param int $guid The entity to contrust a matrix for
	 *
	 * @return string The
	 */
	protected function user_file_matrix($guid) {
		elgg_deprecated_notice('ElggDiskFilestore::user_file_matrix() is deprecated by ::makeFileMatrix()', 1.8);

		return $this->makeFileMatrix($guid);
	}
	// @codingStandardsIgnoreEnd

	/**
	 * Returns a list of attributes to save to the database when saving
	 * the ElggFile object using this file store.
	 *
	 * @return array
	 */
	public function getParameters() {
		return array("dir_root" => $this->dir_root);
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
