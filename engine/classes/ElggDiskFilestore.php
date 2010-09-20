<?php
/**
 * A filestore that uses disk as storage.
 *
 * @warning This should be used by a wrapper class
 * like {@link ElggFile}.
 *
 * @package Elgg.Core
 * @subpackage FileStore.Disk
 * @link http://docs.elgg.org/DataModel/FileStore/Disk
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
	 * @param ElggFile $file
	 * @param string $mode read, write, or append.
	 * @throws InvalidParameterException
	 * @return resource File pointer resource
	 * @todo This really shouldn't try to create directories if not writing.
	 */
	public function open(ElggFile $file, $mode) {
		$fullname = $this->getFilenameOnFilestore($file);

		// Split into path and name
		$ls = strrpos($fullname,"/");
		if ($ls===false) {
			$ls = 0;
		}

		$path = substr($fullname, 0, $ls);
		$name = substr($fullname, $ls);

		// Try and create the directory
		try {
			$this->make_directory_root($path);
		} catch (Exception $e) {

		}

		if (($mode!='write') && (!file_exists($fullname))) {
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
				throw new InvalidParameterException(sprintf(elgg_echo('InvalidParameterException:UnrecognisedFileMode'), $mode));
		}

		return fopen($fullname, $mode);

	}

	/**
	 * Write data to a file.
	 *
	 * @param resource $f File pointer resource
	 * @param mixed $data The data to write.
	 * @return bool
	 */
	public function write($f, $data) {
		return fwrite($f, $data);
	}

	/**
	 * Read data from a file.
	 *
	 * @param resource $f File pointer resource
	 * @param int $length The number of bytes to read
	 * @param inf $offset The number of bytes to start after
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
	 * @return bool
	 */
	public function close($f) {
		return fclose($f);
	}

	/**
	 * Delete an ElggFile file.
	 *
	 * @param ElggFile $file File to delete
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
	 * @param resource $f File resource
	 * @param int $position Position in bytes
	 */
	public function seek($f, $position) {
		return fseek($f, $position);
	}

	/**
	 * Return the current location of the internal pointer
	 *
	 * @param resource $f File pointer resource
	 */
	public function tell($f) {
		return ftell($f);
	}

	/**
	 * Tests for end of file on a file pointer
	 * @param resource $f File pointer resource
	 */
	public function eof($f) {
		return feof($f);
	}

	/**
	 * Returns the file size of an ElggFile file.
	 *
	 * @param ElggFile $file
	 * @return int The file size
	 */
	public function getFileSize(ElggFile $file) {
		return filesize($this->getFilenameOnFilestore($file));
	}

	/**
	 * Returns the filename as saved on disk for an ElggFile object
	 *
	 * @param ElggFile $file
	 * @return string The full path of where the file is stored
	 */
	public function getFilenameOnFilestore(ElggFile $file) {
		$owner = $file->getOwnerEntity();
		if (!$owner) {
			$owner = get_loggedin_user();
		}

		if ((!$owner) || (!$owner->username)) {
			throw new InvalidParameterException(sprintf(elgg_echo('InvalidParameterException:MissingOwner'), $file->getFilename(), $file->guid));
		}

		return $this->dir_root . $this->make_file_matrix($owner->guid) . $file->getFilename();
	}

	/**
	 * Returns the contents of the ElggFile file.
	 *
	 * @param ElggFile $file
	 * @return mixed
	 */
	public function grabFile(ElggFile $file) {
		return file_get_contents($file->getFilenameOnFilestore());
	}

	/**
	 * Tests if an ElggFile file exists.
	 *
	 * @param ElggFile $file
	 * @return bool
	 */
	public function exists(ElggFile $file) {
		return file_exists($this->getFilenameOnFilestore($file));
	}

	/**
	 * Returns the size of all data stored under a directory in the disk store.
	 *
	 * @param string $prefix Optional/ The prefix to check under.
	 * @param string $container_guid The guid of the entity whose data you want to check.
	 * @return int|false
	 */
	public function getSize($prefix = '', $container_guid) {
		if ($container_guid) {
			return get_dir_size($this->dir_root . $this->make_file_matrix($container_guid) . $prefix);
		} else {
			return false;
		}
	}

	/**
	 * Create a directory $dirroot
	 *
	 * @param string $dirroot The full path of the directory to create
	 * @throws IOException
	 * @return true
	 */
	protected function make_directory_root($dirroot) {
		if (!file_exists($dirroot)) {
			if (!@mkdir($dirroot, 0700, true)) {
				throw new IOException(sprintf(elgg_echo('IOException:CouldNotMake'), $dirroot));
			}
		}

		return true;
	}

	/**
	 * Multibyte string tokeniser.
	 *
	 * Splits a string into an array. Will fail safely if mbstring is not installed (although this may still
	 * not handle .
	 *
	 * @param string $string String
	 * @param string $charset The charset, defaults to UTF8
	 * @return array
	 * @todo Can be deprecated since we no long split on usernames
	 */
	private function mb_str_split($string, $charset = 'UTF8') {
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

		return false;
	}

	/**
	 * Construct a file path matrix for an entity.
	 *
	 * @param int The guide of the entity to store the data under.
	 * @return str The path where the entity's data will be stored.
	 */
	protected function make_file_matrix($identifier) {
		if (is_numeric($identifier)) {
			return $this->user_file_matrix($identifier);
		}

		return $this->deprecated_file_matrix($identifier);
	}

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
	 * @return str The
	 * @todo This would work with non-users.  Why is it restricted to only users?
	 */
	protected function user_file_matrix($guid) {
		// lookup the entity
		$user = get_entity($guid);
		if ($user->type != 'user') {
			// only to be used for user directories
			return FALSE;
		}

		if (!$user->time_created) {
			// fall back to deprecated method
			return $this->deprecated_file_matrix($user->username);
		}

		$time_created = date('Y/m/d', $user->time_created);
		return "$time_created/$user->guid/";
	}

	/**
	 * Construct the filename matrix using a string
	 *
	 * Particularly, this is used with a username to generate the file storage
	 * location.
	 *
	 * @deprecated for user directories: use user_file_matrix() instead.
	 *
	 * @param str $filename
	 * @return str
	 */
	protected function deprecated_file_matrix($filename) {
		// throw a warning for using deprecated method
		$error  = 'Deprecated use of ElggDiskFilestore::make_file_matrix. ';
		$error .= 'Username passed instead of guid.';
		elgg_log($error, WARNING);

		$user = new ElggUser($filename);
		return $this->user_file_matrix($user->guid);
	}

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
	 * return bool
	 */
	public function setParameters(array $parameters) {
		if (isset($parameters['dir_root'])) {
			$this->dir_root = $parameters['dir_root'];
			return true;
		}

		return false;
	}
}