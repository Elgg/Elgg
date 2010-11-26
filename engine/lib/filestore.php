<?php
/**
 * Elgg filestore.
 * This file contains classes, interfaces and functions for saving and retrieving data to various file
 * stores.
 *
 * @package Elgg
 * @subpackage API
 */

include_once("objects.php");

/**
 * @class ElggFilestore
 * This class defines the interface for all elgg data repositories.
 */
abstract class ElggFilestore {
	/**
	 * Attempt to open the file $file for storage or writing.
	 *
	 * @param ElggFile $file
	 * @param string $mode "read", "write", "append"
	 * @return mixed A handle to the opened file or false on error.
	 */
	abstract public function open(ElggFile $file, $mode);

	/**
	 * Write data to a given file handle.
	 *
	 * @param mixed $f The file handle - exactly what this is depends on the file system
	 * @param string $data The binary string of data to write
	 * @return int Number of bytes written.
	 */
	abstract public function write($f, $data);

	/**
	 * Read data from a filestore.
	 *
	 * @param mixed $f The file handle
	 * @param int $length Length in bytes to read.
	 * @param int $offset The optional offset.
	 * @return mixed String of data or false on error.
	 */
	abstract public function read($f, $length, $offset = 0);

	/**
	 * Seek a given position within a file handle.
	 *
	 * @param mixed $f The file handle.
	 * @param int $position The position.
	 */
	abstract public function seek($f, $position);

	/**
	 * Return a whether the end of a file has been reached.
	 *
	 * @param mixed $f The file handle.
	 * @return boolean
	 */
	abstract public function eof($f);

	/**
	 * Return the current position in an open file.
	 *
	 * @param mixed $f The file handle.
	 * @return int
	 */
	abstract public function tell($f);

	/**
	 * Close a given file handle.
	 *
	 * @param mixed $f
	 */
	abstract public function close($f);

	/**
	 * Delete the file associated with a given file handle.
	 *
	 * @param ElggFile $file
	 */
	abstract public function delete(ElggFile $file);

	/**
	 * Return the size in bytes for a given file.
	 *
	 * @param ElggFile $file
	 */
	abstract public function getFileSize(ElggFile $file);

	/**
	 * Return the filename of a given file as stored on the filestore.
	 *
	 * @param ElggFile $file
	 */
	abstract public function getFilenameOnFilestore(ElggFile $file);

	/**
	 * Get the filestore's creation parameters as an associative array.
	 * Used for serialisation and for storing the creation details along side a file object.
	 *
	 * @return array
	 */
	abstract public function getParameters();

	/**
	 * Set the parameters from the associative array produced by $this->getParameters().
	 */
	abstract public function setParameters(array $parameters);

	/**
	 * Get the contents of the whole file.
	 *
	 * @param mixed $file The file handle.
	 * @return mixed The file contents.
	 */
	abstract public function grabFile(ElggFile $file);

	/**
	 * Return whether a file physically exists or not.
	 *
	 * @param ElggFile $file
	 */
	abstract public function exists(ElggFile $file);
}

/**
 * @class ElggDiskFilestore
 * This class uses disk storage to save data.
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
			// include file guid and owner guid to make debugging easier
			$owner_guid = 0;
			if ($owner) {
				$owner_guid = $owner->guid;
			}
			throw new InvalidParameterException(sprintf(elgg_echo('InvalidParameterException:MissingOwner'), $file->getFilename(), $file->guid, $owner_guid));
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

/**
 * @class ElggFile
 * This class represents a physical file.
 *
 * Usage:
 *		Create a new ElggFile object and specify a filename, and optionally a FileStore (if one isn't specified
 *		then the default is assumed.
 *
 * 		Open the file using the appropriate mode, and you will be able to read and write to the file.
 *
 * 		Optionally, you can also call the file's save() method, this will turn the file into an entity in the
 * 		system and permit you to do things like attach tags to the file etc. This is not done automatically since
 * 		there are many occasions where you may want access to file data on datastores using the ElggFile interface
 * 		but do not want to create an Entity reference to it in the system (temporary files for example).
 *
 */
class ElggFile extends ElggObject {
	/** Filestore */
	private $filestore;

	/** File handle used to identify this file in a filestore. Created by open. */
	private $handle;

	protected function initialise_attributes() {
		parent::initialise_attributes();

		$this->attributes['subtype'] = "file";
	}

	public function __construct($guid = null) {
		parent::__construct($guid);

		// Set default filestore
		$this->filestore = $this->getFilestore();
	}

	/**
	 * Set the filename of this file.
	 *
	 * @param string $name The filename.
	 */
	public function setFilename($name) {
		$this->filename = $name;
	}

	/**
	 * Return the filename.
	 */
	public function getFilename() {
		return $this->filename;
	}

	/**
	 * Return the filename of this file as it is/will be stored on the filestore, which may be different
	 * to the filename.
	 */
	public function getFilenameOnFilestore() {
		return $this->filestore->getFilenameOnFilestore($this);
	}

	/*
	 * Return the size of the filestore associated with this file
	 *
	 */
	public function getFilestoreSize($prefix='',$container_guid=0) {
		if (!$container_guid) {
			$container_guid = $this->container_guid;
		}
		$fs = $this->getFilestore();
		return $fs->getSize($prefix,$container_guid);
	}

	/**
	 * Get the mime type of the file.
	 */
	public function getMimeType() {
		if ($this->mimetype) {
			return $this->mimetype;
		}

		// @todo Guess mimetype if not here
	}

	/**
	 * Set the mime type of the file.
	 *
	 * @param $mimetype The mimetype
	 */
	public function setMimeType($mimetype) {
		return $this->mimetype = $mimetype;
	}

	/**
	 * Set the optional file description.
	 *
	 * @param string $description The description.
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Open the file with the given mode
	 *
	 * @param string $mode Either read/write/append
	 */
	public function open($mode) {
		if (!$this->getFilename()) {
			throw new IOException(elgg_echo('IOException:MissingFileName'));
		}

		// See if file has already been saved
		// seek on datastore, parameters and name?

		// Sanity check
		if (
			($mode!="read") &&
			($mode!="write") &&
			($mode!="append")
		) {
			throw new InvalidParameterException(sprintf(elgg_echo('InvalidParameterException:UnrecognisedFileMode'), $mode));
		}

		// Get the filestore
		$fs = $this->getFilestore();

		// Ensure that we save the file details to object store
		//$this->save();

		// Open the file handle
		$this->handle = $fs->open($this, $mode);

		return $this->handle;
	}

	/**
	 * Write some data.
	 *
	 * @param string $data The data
	 */
	public function write($data) {
		$fs = $this->getFilestore();

		return $fs->write($this->handle, $data);
	}

	/**
	 * Read some data.
	 *
	 * @param int $length Amount to read.
	 * @param int $offset The offset to start from.
	 */
	public function read($length, $offset = 0) {
		$fs = $this->getFilestore();

		return $fs->read($this->handle, $length, $offset);
	}

	/**
	 * Gets the full contents of this file.
	 *
	 * @return mixed The file contents.
	 */
	public function grabFile() {
		$fs = $this->getFilestore();
		return $fs->grabFile($this);
	}

	/**
	 * Close the file and commit changes
	 */
	public function close() {
		$fs = $this->getFilestore();

		if ($fs->close($this->handle)) {
			$this->handle = NULL;

			return true;
		}

		return false;
	}

	/**
	 * Delete this file.
	 */
	public function delete() {
		$fs = $this->getFilestore();
		if ($fs->delete($this)) {
			return parent::delete();
		}
	}

	/**
	 * Seek a position in the file.
	 *
	 * @param int $position
	 */
	public function seek($position) {
		$fs = $this->getFilestore();

		return $fs->seek($this->handle, $position);
	}

	/**
	 * Return the current position of the file.
	 *
	 * @return int The file position
	 */
	public function tell() {
		$fs = $this->getFilestore();

		return $fs->tell($this->handle);
	}

	/**
	 * Return the size of the file in bytes.
	 */
	public function size() {
		return $this->filestore->getFileSize($this);
	}

	/**
	 * Return a boolean value whether the file handle is at the end of the file
	 */
	public function eof() {
		$fs = $this->getFilestore();

		return $fs->eof($this->handle);
	}

	public function exists() {
		$fs = $this->getFilestore();

		return $fs->exists($this);
	}

	/**
	 * Set a filestore.
	 *
	 * @param ElggFilestore $filestore The file store.
	 */
	public function setFilestore(ElggFilestore $filestore) {
		$this->filestore = $filestore;
	}

	/**
	 * Return a filestore suitable for saving this file.
	 * This filestore is either a pre-registered filestore, a filestore loaded from metatags saved
	 * along side this file, or the system default.
	 */
	protected function getFilestore() {
		// Short circuit if already set.
		if ($this->filestore) {
			return $this->filestore;
		}

		// If filestore meta set then retrieve filestore
		// @todo Better way of doing this?
		$metas = get_metadata_for_entity($this->guid);
		$parameters = array();
		if (is_array($metas)) {
			foreach ($metas as $meta) {
				if (strpos($meta->name, "filestore::")!==false) {
					// Filestore parameter tag
					$comp = explode("::", $meta->name);
					$name = $comp[1];

					$parameters[$name] = $meta->value;
				}
			}
		}

		if (isset($parameters['filestore'])) {
			if (!class_exists($parameters['filestore'])) {
				$msg = sprintf(elgg_echo('ClassNotFoundException:NotFoundNotSavedWithFile'),
								$parameters['filestore'],
								$this->guid);
				throw new ClassNotFoundException($msg);
			}

			// Create new filestore object
			$this->filestore = new $parameters['filestore']();

			$this->filestore->setParameters($parameters);
		} else {
			// @todo - should we log error if filestore not set
		}


		// if still nothing then set filestore to default
		if (!$this->filestore) {
			$this->filestore = get_default_filestore();
		}

		return $this->filestore;
	}

	public function save() {
		if (!parent::save()) {
			return false;
		}

		// Save datastore metadata
		$params = $this->filestore->getParameters();
		foreach ($params as $k => $v) {
			$this->setMetaData("filestore::$k", $v);
		}

		// Now make a note of the filestore class
		$this->setMetaData("filestore::filestore", get_class($this->filestore));

		return true;
	}
}

/**
 * Get the size of the specified directory.
 *
 * @param string $dir The full path of the directory
 * @return int The size of the directory.
 */
function get_dir_size($dir, $totalsize = 0){
	$handle = @opendir($dir);
	while ($file = @readdir ($handle)){
		if (eregi("^\.{1,2}$", $file)) {
			continue;
		}
		if(is_dir($dir . $file)) {
			$totalsize = get_dir_size($dir . $file . "/", $totalsize);
		} else{
			$totalsize += filesize($dir . $file);
		}
	}
	@closedir($handle);

	return($totalsize);
}

/**
 * Get the contents of an uploaded file.
 * (Returns false if there was an issue.)
 *
 * @param string $input_name The name of the file input field on the submission form
 * @return mixed|false The contents of the file, or false on failure.
 */
function get_uploaded_file($input_name) {
	// If the file exists ...
	if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {
		return file_get_contents($_FILES[$input_name]['tmp_name']);
	}
	return false;
}

/**
 * Gets the jpeg contents of the resized version of an uploaded image
 * (Returns false if the uploaded file was not an image)
 *
 * @param string $input_name The name of the file input field on the submission form
 * @param int $maxwidth The maximum width of the resized image
 * @param int $maxheight The maximum height of the resized image
 * @param true|false $square If set to true, will take the smallest of maxwidth and maxheight and use it to set the dimensions on all size; the image will be cropped.
 * @param true|false $upscale Resize images smaller than $maxwidth x $maxheight?
 * @return false|mixed The contents of the resized image, or false on failure
 */
function get_resized_image_from_uploaded_file($input_name, $maxwidth, $maxheight, $square = false, $upscale = false) {
	// If our file exists ...
	if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {
		return get_resized_image_from_existing_file($_FILES[$input_name]['tmp_name'], $maxwidth, $maxheight, $square, 0, 0, 0, 0, $upscale);
	}
	return false;
}

/**
 * Gets the jpeg contents of the resized version of an already uploaded image
 * (Returns false if the file was not an image)
 *
 * @param string $input_name The name of the file on the disk
 * @param int $maxwidth The desired width of the resized image
 * @param int $maxheight The desired height of the resized image
 * @param true|false $square If set to true, takes the smallest of maxwidth and
 * 			maxheight and use it to set the dimensions on the new image. If no
 * 			crop parameters are set, the largest square that fits in the image
 * 			centered will be used for the resize. If square, the crop must be a
 * 			square region.
 * @param int $x1 x coordinate for top, left corner
 * @param int $y1 y coordinate for top, left corner
 * @param int $x2 x coordinate for bottom, right corner
 * @param int $y2 y coordinate for bottom, right corner
 * @param bool $upscale Resize images smaller than $maxwidth x $maxheight?
 * @return false|mixed The contents of the resized image, or false on failure
 */
function get_resized_image_from_existing_file($input_name, $maxwidth, $maxheight, $square = FALSE, $x1 = 0, $y1 = 0, $x2 = 0, $y2 = 0, $upscale = FALSE) {
	// Get the size information from the image
	$imgsizearray = getimagesize($input_name);
	if ($imgsizearray == FALSE) {
		return FALSE;
	}

	$width = $imgsizearray[0];
	$height = $imgsizearray[1];

	$accepted_formats = array(
		'image/jpeg' => 'jpeg',
		'image/pjpeg' => 'jpeg',
		'image/png' => 'png',
		'image/x-png' => 'png',
		'image/gif' => 'gif'
	);

	// make sure the function is available
	$load_function = "imagecreatefrom" . $accepted_formats[$imgsizearray['mime']];
	if (!is_callable($load_function)) {
		return FALSE;
	}

	// get the parameters for resizing the image
	$options = array(
		'maxwidth' => $maxwidth,
		'maxheight' => $maxheight,
		'square' => $square,
		'upscale' => $upscale,
		'x1' => $x1,
		'y1' => $y1,
		'x2' => $x2,
		'y2' => $y2,
	);
	$params = get_image_resize_parameters($width, $height, $options);
	if ($params == FALSE) {
		return FALSE;
	}

	// load original image
	$original_image = $load_function($input_name);
	if (!$original_image) {
		return FALSE;
	}

	// allocate the new image
	$new_image = imagecreatetruecolor($params['newwidth'], $params['newheight']);
	if (!$new_image) {
		return FALSE;
	}

	$rtn_code = imagecopyresampled(	$new_image,
									$original_image,
									0,
									0,
									$params['xoffset'],
									$params['yoffset'],
									$params['newwidth'],
									$params['newheight'],
									$params['selectionwidth'],
									$params['selectionheight']);
	if (!$rtn_code) {
		return FALSE;
	}

	// grab a compressed jpeg version of the image
	ob_start();
	imagejpeg($new_image, NULL, 90);
	$jpeg = ob_get_clean();

	imagedestroy($new_image);
	imagedestroy($original_image);

	return $jpeg;
}

/**
 * Calculate the parameters for resizing an image
 *
 * @param int $width Width of the original image
 * @param int $height Height of the original image
 * @param array $options See $defaults for the options
 * @return array or FALSE
 * @since 1.7.2
 */
function get_image_resize_parameters($width, $height, $options) {

	$defaults = array(
		'maxwidth' => 100,
		'maxheight' => 100,
		
		'square' => FALSE,
		'upscale' => FALSE,

		'x1' => 0,
		'y1' => 0,
		'x2' => 0,
		'y2' => 0,
	);

	$options = array_merge($defaults, $options);

	extract($options);

	// crop image first?
	$crop = TRUE;
	if ($x1 == 0 && $y1 == 0 && $x2 == 0 && $y2 == 0) {
		$crop = FALSE;
	}

	// how large a section of the image has been selected
	if ($crop) {
		$selection_width = $x2 - $x1;
		$selection_height = $y2 - $y1;
	} else {
		// everything selected if no crop parameters
		$selection_width = $width;
		$selection_height = $height;
	}

	// determine cropping offsets
	if ($square) {
		// asking for a square image back

		// detect case where someone is passing crop parameters that are not for a square
		if ($crop == TRUE && $selection_width != $selection_height) {
			return FALSE;
		}

		// size of the new square image
		$new_width = $new_height = min($maxwidth, $maxheight);

		// find largest square that fits within the selected region
		$selection_width = $selection_height = min($selection_width, $selection_height);

		// set offsets for crop
		if ($crop) {
			$widthoffset = $x1;
			$heightoffset = $y1;
			$width = $x2 - $x1;
			$height = $width;
		} else {
			// place square region in the center
			$widthoffset = floor(($width - $selection_width) / 2);
			$heightoffset = floor(($height - $selection_height) / 2);
		}
	} else {
		// non-square new image
		$new_width = $maxwidth;
		$new_height = $maxheight;

		// maintain aspect ratio of original image/crop
		if (($selection_height / (float)$new_height) > ($selection_width / (float)$new_width)) {
			$new_width = floor($new_height * $selection_width / (float)$selection_height);
		} else {
			$new_height = floor($new_width * $selection_height / (float)$selection_width);
		}

		// by default, use entire image
		$widthoffset = 0;
		$heightoffset = 0;

		if ($crop) {
			$widthoffset = $x1;
			$heightoffset = $y1;
		}
	}

	if (!$upscale && ($selection_height < $new_height || $selection_width < $new_width)) {
		// we cannot upscale and selected area is too small so we decrease size of returned image
		if ($square) {
			$new_height = $selection_height;
			$new_width = $selection_width;
		} else {
			if ($selection_height < $new_height && $selection_width < $new_width) {
				$new_height = $selection_height;
				$new_width = $selection_width;
			}
		}
	}

	$params = array(
		'newwidth' => $new_width,
		'newheight' => $new_height,
		'selectionwidth' => $selection_width,
		'selectionheight' => $selection_height,
		'xoffset' => $widthoffset,
		'yoffset' => $heightoffset,
	);

	return $params;
}

// putting these here for now
function file_delete($guid) {
	if ($file = get_entity($guid)) {
		if ($file->canEdit()) {
			$container = get_entity($file->container_guid);

			$thumbnail = $file->thumbnail;
			$smallthumb = $file->smallthumb;
			$largethumb = $file->largethumb;
			if ($thumbnail) {
				$delfile = new ElggFile();
				$delfile->owner_guid = $file->owner_guid;
				$delfile->setFilename($thumbnail);
				$delfile->delete();
			}
			if ($smallthumb) {
				$delfile = new ElggFile();
				$delfile->owner_guid = $file->owner_guid;
				$delfile->setFilename($smallthumb);
				$delfile->delete();
			}
			if ($largethumb) {
				$delfile = new ElggFile();
				$delfile->owner_guid = $file->owner_guid;
				$delfile->setFilename($largethumb);
				$delfile->delete();
			}

			return $file->delete();
		}
	}

	return false;
}

/**
 * Returns an overall file type from the mimetype
 *
 * @param string $mimetype The MIME type
 * @return string The overall type
 */
function file_get_general_file_type($mimetype) {
	switch($mimetype) {

		case "application/msword":
			return "document";
			break;
		case "application/pdf":
			return "document";
			break;
	}

	if (substr_count($mimetype,'text/')) {
		return "document";
	}

	if (substr_count($mimetype,'audio/')) {
		return "audio";
	}

	if (substr_count($mimetype,'image/')) {
		return "image";
	}

	if (substr_count($mimetype,'video/')) {
		return "video";
	}

	if (substr_count($mimetype,'opendocument')) {
		return "document";
	}

	return "general";
}

function file_handle_upload($prefix,$subtype,$plugin) {
	$desc = get_input("description");
	$tags = get_input("tags");
	$tags = explode(",", $tags);
	$folder = get_input("folder_text");
	if (!$folder) {
		$folder = get_input("folder_select");
	}
	$access_id = (int) get_input("access_id");
	$container_guid = (int) get_input('container_guid', 0);
	if (!$container_guid) {
		$container_guid == get_loggedin_userid();
	}

	// Extract file from, save to default filestore (for now)

	// see if a plugin has set a quota for this user
	$file_quota = trigger_plugin_hook("$plugin:quotacheck",'user',array('container_guid'=>$container_guid));
	if (!$file_quota) {
		// no, see if there is a generic quota set
		$file_quota = get_plugin_setting('quota', $plugin);
	}
	if ($file_quota) {
		// convert to megabytes
		$file_quota = $file_quota*1000*1024;
	}

	// handle uploaded files
	$number_of_files = get_input('number_of_files',0);
	$quota_exceeded = false;
	$bad_mime_type = false;

	for ($i = 0; $i < $number_of_files; $i++) {
		$title = get_input("title_".$i);
		$uploaded = $_FILES["upload_".$i];
		if (!$uploaded || !$uploaded['name']) {
			// no such file, so skip it
			continue;
		}
		if ($plugin == "photo") {
			// do a mime type test
			if (in_array($uploaded['type'],array('image/jpeg','image/gif','image/png','image/jpg','image/jpe','image/pjpeg','image/x-png'))) {
				$file = new PhotoPluginFile();
			} else {
				$bad_mime_type = true;
				break;
			}
		} else {
			$file = new FilePluginFile();
		}
		$dir_size = $file->getFilestoreSize($prefix,$container_guid);
		$filestorename = strtolower(time().$uploaded['name']);
		$file->setFilename($prefix.$filestorename);
		$file->setMimeType($uploaded['type']);

		$file->originalfilename = $uploaded['name'];

		$file->subtype = $subtype;

		$file->access_id = $access_id;

		$uf = get_uploaded_file('upload_'.$i);

		if ($file_quota) {
			$file_size = strlen($uf);
			if (($dir_size + $file_size) > $file_quota) {
				$quota_exceeded = true;
			}
		}

		if (!$quota_exceeded) {
			// all clear, so try to save the data

			$file->open("write");
			$file->write($uf);
			$file->close();

			$file->title = $title;
			$file->description = $desc;
			if ($container_guid) {
				$file->container_guid = $container_guid;
			}

			// Save tags
			$file->tags = $tags;

			$file->simpletype = file_get_general_file_type($uploaded['type']);
			$file->folder = $folder;

			$result = $file->save();

			if ($result) {

				// Generate thumbnail (if image)
				if (substr_count($file->getMimeType(),'image/')) {
					$thumbnail = get_resized_image_from_existing_file($file->getFilenameOnFilestore(),60,60, true);
					$thumbsmall = get_resized_image_from_existing_file($file->getFilenameOnFilestore(),153,153, true);
					$thumblarge = get_resized_image_from_existing_file($file->getFilenameOnFilestore(),600,600, false);
					if ($thumbnail) {
						$thumb = new ElggFile();
						$thumb->setMimeType($uploaded['type']);

						$thumb->setFilename($prefix."thumb".$filestorename);
						$thumb->open("write");
						$thumb->write($thumbnail);
						$thumb->close();

						$file->thumbnail = $prefix."thumb".$filestorename;

						$thumb->setFilename($prefix."smallthumb".$filestorename);
						$thumb->open("write");
						$thumb->write($thumbsmall);
						$thumb->close();
						$file->smallthumb = $prefix."smallthumb".$filestorename;

						$thumb->setFilename($prefix."largethumb".$filestorename);
						$thumb->open("write");
						$thumb->write($thumblarge);
						$thumb->close();
						$file->largethumb = $prefix."largethumb".$filestorename;
					}
				}

				// add to this user's file folders
				file_add_to_folders($folder,$container_guid,$plugin);

				add_to_river("river/object/$plugin/create",'create',$_SESSION['user']->guid,$file->guid);
			} else {
				break;
			}
		} else {
			break;
		}
	}

	if ($quota_exceeded) {
		echo elgg_echo("$plugin:quotaexceeded");
	} else if ($bad_mime_type)	{
		echo elgg_echo("$plugin:badmimetype");
	} else if ($result) {
		if ($number_of_files > 1) {
			echo elgg_echo("$plugin:saved_multi");
		} else {
			echo elgg_echo("$plugin:saved");
		}
	} else {
		if ($number_of_files > 1) {
			echo elgg_echo("$plugin:uploadfailed_multi");
		} else {
			echo elgg_echo("$plugin:uploadfailed");
		}
	}
}

function file_add_to_folders($folder,$container_guid,$plugin) {
	if ($container_guid && ($container = get_entity($container_guid))) {
		$folder_field_name = 'elgg_'.$plugin.'_folders';
		$folders = $container->$folder_field_name;
		if ($folders) {
			if (is_array($folders)) {
				if (!in_array($folder,$folders)) {
					$folders[] = $folder;
					$container->$folder_field_name = $folders;
				}
			} else {
				if ($folders != $folder) {
					$container->$folder_field_name = array($folders,$folder);
				}
			}
		} else {
			$container->$folder_field_name = $folder;
		}
	}
}

function file_handle_save($forward,$plugin) {
	// Get variables
	$title = get_input("title");
	$desc = get_input("description");
	$tags = get_input("tags");
	$folder = get_input("folder_text");
	if (!$folder) {
		$folder = get_input("folder_select");
	}
	$access_id = (int) get_input("access_id");

	$guid = (int) get_input('file_guid');

	if (!$file = get_entity($guid)) {
		register_error(elgg_echo("$plugin:uploadfailed"));
		forward($forward . $_SESSION['user']->username);
		exit;
	}

	$result = false;

	$container_guid = $file->container_guid;
	$container = get_entity($container_guid);

	if ($file->canEdit()) {
		$file->access_id = $access_id;
		$file->title = $title;
		$file->description = $desc;
		$file->folder = $folder;
		// add to this user's file folders
		file_add_to_folders($folder,$container_guid,$plugin);

		// Save tags
		$tags = explode(",", $tags);
		$file->tags = $tags;

		$result = $file->save();
	}

	if ($result) {
		system_message(elgg_echo("$plugin:saved"));
	} else {
		register_error(elgg_echo("$plugin:uploadfailed"));
	}
	forward($forward . $container->username);
}

/**
 * Manage a file download.
 *
 * @param unknown_type $plugin
 * @param unknown_type $file_guid If not specified then file_guid will be found in input.
 */
function file_manage_download($plugin, $file_guid = "") {
	// Get the guid
	$file_guid = (int)$file_guid;

	if (!$file_guid) {
		$file_guid = (int)get_input("file_guid");
	}

	// Get the file
	$file = get_entity($file_guid);

	if ($file) {
		$mime = $file->getMimeType();
		if (!$mime) {
			$mime = "application/octet-stream";
		}

		$filename = $file->originalfilename;

		header("Content-type: $mime");
		if (strpos($mime, "image/")!==false) {
			header("Content-Disposition: inline; filename=\"$filename\"");
		} else {
			header("Content-Disposition: attachment; filename=\"$filename\"");
		}

		echo $file->grabFile();
		exit;
	} else {
		register_error(elgg_echo("$plugin:downloadfailed"));
	}
}

/**
 * Manage the download of a file icon.
 *
 * @param unknown_type $plugin
 * @param unknown_type $file_guid The guid, if not specified this is obtained from the input.
 */
function file_manage_icon_download($plugin, $file_guid = "") {
	// Get the guid
	$file_guid = (int)$file_guid;

	if (!$file_guid) {
		$file_guid = (int)get_input("file_guid");
	}

	// Get the file
	$file = get_entity($file_guid);

	if ($file) {
		$mime = $file->getMimeType();
		if (!$mime) {
			$mime = "application/octet-stream";
		}

		$filename = $file->thumbnail;

		header("Content-type: $mime");
		if (strpos($mime, "image/")!==false) {
			header("Content-Disposition: inline; filename=\"$filename\"");
		} else {
			header("Content-Disposition: attachment; filename=\"$filename\"");
		}

		$readfile = new ElggFile();
		$readfile->owner_guid = $file->owner_guid;
		$readfile->setFilename($filename);

		/*
		if ($file->open("read"));
		{
			while (!$file->eof())
			{
				echo $file->read(10240, $file->tell());
			}
		}
		*/

		$contents = $readfile->grabFile();
		if (empty($contents)) {
			echo file_get_contents(dirname(dirname(__FILE__)) . "/graphics/icons/general.jpg" );
		} else {
			echo $contents;
		}
		exit;
	} else {
		register_error(elgg_echo("$plugin:downloadfailed"));
	}
}

function file_display_thumbnail($file_guid,$size) {
	// Get file entity
	if ($file = get_entity($file_guid)) {
		$simpletype = $file->simpletype;
		if ($simpletype == "image") {
			// Get file thumbnail
			if ($size == "small") {
				$thumbfile = $file->smallthumb;
			} else {
				$thumbfile = $file->largethumb;
			}

			// Grab the file
			if ($thumbfile && !empty($thumbfile)) {
				$readfile = new ElggFile();
				$readfile->owner_guid = $file->owner_guid;
				$readfile->setFilename($thumbfile);
				$mime = $file->getMimeType();
				$contents = $readfile->grabFile();

				header("Content-type: $mime");
				echo $contents;
				exit;
			}
		}
	}
}

function file_set_page_owner($file) {
	$page_owner = page_owner_entity();
	if ($page_owner === false || is_null($page_owner)) {
		$container_guid = $file->container_guid;
		if (!empty($container_guid)) {
			if ($page_owner = get_entity($container_guid)) {
				set_page_owner($page_owner->guid);
			}
		}

		if (empty($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($_SESSION['guid']);
		}
	}
}

/**
 * Recursively delete a directory
 *
 * @param str $directory
 */
function delete_directory($directory) {
	// sanity check: must be a directory
	if (!$handle = opendir($directory)) {
		return FALSE;
	}

	// loop through all files
	while (($file = readdir($handle)) !== FALSE) {
		if (in_array($file, array('.', '..'))) {
			continue;
		}

		$path = "$directory/$file";
		if (is_dir($path)) {
			// recurse down through directory
			if (!delete_directory($path)) {
				return FALSE;
			}
		} else {
			// delete file
			unlink($path);
		}
	}

	// remove empty directory
	closedir($handle);
	return rmdir($directory);
}

/**
 * Removes all user files
 *
 * @param ElggUser $user
 * @return void
 */
function clear_user_files($user) {
	global $CONFIG;

	$time_created = date('Y/m/d', (int)$user->time_created);
	$file_path = "$CONFIG->dataroot$time_created/$user->guid";
	if (file_exists($file_path)) {
		delete_directory($file_path);
	}
}


/// Variable holding the default datastore
$DEFAULT_FILE_STORE = NULL;

/**
 * Return the default filestore.
 *
 * @return ElggFilestore
 */
function get_default_filestore() {
	global $DEFAULT_FILE_STORE;

	return $DEFAULT_FILE_STORE;
}

/**
 * Set the default filestore for the system.
 */
function set_default_filestore(ElggFilestore $filestore) {
	global $DEFAULT_FILE_STORE;

	$DEFAULT_FILE_STORE = $filestore;

	return true;
}

/**
 * Run once and only once.
 */
function filestore_run_once() {
	// Register a class
	add_subtype("object", "file", "ElggFile");
}

/**
 * Initialise the file modules.
 * Listens to system boot and registers any appropriate file types and classes
 */
function filestore_init() {
	global $CONFIG;

	// Now register a default filestore
	set_default_filestore(new ElggDiskFilestore($CONFIG->dataroot));

	// Now run this stuff, but only once
	run_function_once("filestore_run_once");
}

// Register a startup event
register_elgg_event_handler('init', 'system', 'filestore_init', 100);

// Unit testing
register_plugin_hook('unit_test', 'system', 'filestore_test');
function filestore_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = "{$CONFIG->path}engine/tests/objects/filestore.php";
	return $value;
}
