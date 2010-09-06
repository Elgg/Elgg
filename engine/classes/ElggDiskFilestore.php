<?php
/**
 * @class ElggDiskFilestore
 * This class uses disk storage to save data.
 * @author Curverider Ltd
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

	public function write($f, $data) {
		return fwrite($f, $data);
	}

	public function read($f, $length, $offset = 0) {
		if ($offset) {
			$this->seek($f, $offset);
		}

		return fread($f, $length);
	}

	public function close($f) {
		return fclose($f);
	}

	public function delete(ElggFile $file) {
		$filename = $this->getFilenameOnFilestore($file);
		if (file_exists($filename)) {
			return unlink($filename);
		} else {
			return true;
		}
	}

	public function seek($f, $position) {
		return fseek($f, $position);
	}

	public function tell($f) {
		return ftell($f);
	}

	public function eof($f) {
		return feof($f);
	}

	public function getFileSize(ElggFile $file) {
		return filesize($this->getFilenameOnFilestore($file));
	}

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

	public function grabFile(ElggFile $file) {
		return file_get_contents($file->getFilenameOnFilestore());
	}

	public function exists(ElggFile $file) {
		return file_exists($this->getFilenameOnFilestore($file));
	}

	public function getSize($prefix,$container_guid) {
		if ($container_guid) {
			return get_dir_size($this->dir_root.$this->make_file_matrix($container_guid).$prefix);
		} else {
			return false;
		}
	}

	/**
	 * Make the directory root.
	 *
	 * @param string $dirroot
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
	 * Construct the filename matrix.
	 *
	 * @param int | string $identifier
	 * @return str
	 */
	protected function make_file_matrix($identifier) {
		if (is_numeric($identifier)) {
			return $this->user_file_matrix($identifier);
		}

		return $this->deprecated_file_matrix($identifier);
	}

	/**
	 * Construct the filename matrix with user info
	 *
	 * This method will generate a matrix using the entity's creation time and
	 * unique guid. This is intended only to determine a user's data directory.
	 *
	 * @param int $guid
	 * @return str
	 */
	protected function user_file_matrix($guid) {
		// lookup the entity
		$user = get_entity($guid);
		if ($user->type != 'user')
		{
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

	public function getParameters() {
		return array("dir_root" => $this->dir_root);
	}

	public function setParameters(array $parameters) {
		if (isset($parameters['dir_root'])) {
			$this->dir_root = $parameters['dir_root'];
			return true;
		}

		return false;
	}
}
