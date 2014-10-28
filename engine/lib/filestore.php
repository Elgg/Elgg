<?php
/**
 * Elgg filestore.
 * This file contains functions for saving and retrieving data from files.
 *
 * @package Elgg.Core
 * @subpackage DataModel.FileStorage
 */

/**
 * Get the size of the specified directory.
 *
 * @param string $dir       The full path of the directory
 * @param int    $totalsize Add to current dir size
 *
 * @return int The size of the directory.
 */
function get_dir_size($dir, $totalsize = 0) {
	$handle = @opendir($dir);
	while ($file = @readdir($handle)) {
		if (eregi("^\.{1,2}$", $file)) {
			continue;
		}
		if (is_dir($dir . $file)) {
			$totalsize = get_dir_size($dir . $file . "/", $totalsize);
		} else {
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
 *
 * @return mixed|false The contents of the file, or false on failure.
 */
function get_uploaded_file($input_name) {
	$files = _elgg_services()->request->files;
	if (!$files->has($input_name)) {
		return false;
	}

	$file = $files->get($input_name);
	if (elgg_extract('error', $file) !== 0) {
		return false;
	}

	return file_get_contents(elgg_extract('tmp_name', $file));
}

/**
 * Gets the jpeg contents of the resized version of an uploaded image
 * (Returns false if the uploaded file was not an image)
 *
 * @param string $input_name The name of the file input field on the submission form
 * @param int    $maxwidth   The maximum width of the resized image
 * @param int    $maxheight  The maximum height of the resized image
 * @param bool   $square     If set to true, will take the smallest
 *                           of maxwidth and maxheight and use it to set the
 *                           dimensions on all size; the image will be cropped.
 * @param bool   $upscale    Resize images smaller than $maxwidth x $maxheight?
 *
 * @return false|mixed The contents of the resized image, or false on failure
 */
function get_resized_image_from_uploaded_file($input_name, $maxwidth, $maxheight,
$square = false, $upscale = false) {
	$files = _elgg_services()->request->files;
	if (!$files->has($input_name)) {
		return false;
	}

	$file = $files->get($input_name);
	if (elgg_extract('error', $file) !== 0) {
		return false;
	}

	return get_resized_image_from_existing_file(elgg_extract('tmp_name', $file), $maxwidth,
		$maxheight, $square, 0, 0, 0, 0, $upscale);
}

/**
 * Gets the jpeg contents of the resized version of an already uploaded image
 * (Returns false if the file was not an image)
 *
 * @param string $input_name The name of the file on the disk
 * @param int    $maxwidth   The desired width of the resized image
 * @param int    $maxheight  The desired height of the resized image
 * @param bool   $square     If set to true, takes the smallest of maxwidth and
 * 			                 maxheight and use it to set the dimensions on the new image.
 *                           If no crop parameters are set, the largest square that fits
 *                           in the image centered will be used for the resize. If square,
 *                           the crop must be a square region.
 * @param int    $x1         x coordinate for top, left corner
 * @param int    $y1         y coordinate for top, left corner
 * @param int    $x2         x coordinate for bottom, right corner
 * @param int    $y2         y coordinate for bottom, right corner
 * @param bool   $upscale    Resize images smaller than $maxwidth x $maxheight?
 *
 * @return false|mixed The contents of the resized image, or false on failure
 */
function get_resized_image_from_existing_file($input_name, $maxwidth, $maxheight, $square = false,
$x1 = 0, $y1 = 0, $x2 = 0, $y2 = 0, $upscale = false) {

	// Get the size information from the image
	$imgsizearray = getimagesize($input_name);
	if ($imgsizearray == false) {
		return false;
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
		return false;
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
	if ($params == false) {
		return false;
	}

	// load original image
	$original_image = call_user_func($load_function, $input_name);
	if (!$original_image) {
		return false;
	}

	// allocate the new image
	$new_image = imagecreatetruecolor($params['newwidth'], $params['newheight']);
	if (!$new_image) {
		return false;
	}

	// color transparencies white (default is black)
	imagefilledrectangle(
		$new_image, 0, 0, $params['newwidth'], $params['newheight'],
		imagecolorallocate($new_image, 255, 255, 255)
	);

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
		return false;
	}

	// grab a compressed jpeg version of the image
	ob_start();
	imagejpeg($new_image, null, 90);
	$jpeg = ob_get_clean();

	imagedestroy($new_image);
	imagedestroy($original_image);

	return $jpeg;
}

/**
 * Calculate the parameters for resizing an image
 *
 * @param int   $width   Width of the original image
 * @param int   $height  Height of the original image
 * @param array $options See $defaults for the options
 *
 * @return array or false
 * @since 1.7.2
 */
function get_image_resize_parameters($width, $height, $options) {

	$defaults = array(
		'maxwidth' => 100,
		'maxheight' => 100,

		'square' => false,
		'upscale' => false,

		'x1' => 0,
		'y1' => 0,
		'x2' => 0,
		'y2' => 0,
	);

	$options = array_merge($defaults, $options);

	// Avoiding extract() because it hurts static analysis
	$maxwidth = $options['maxwidth'];
	$maxheight = $options['maxheight'];
	$square = $options['square'];
	$upscale = $options['upscale'];
	$x1 = $options['x1'];
	$y1 = $options['y1'];
	$x2 = $options['x2'];
	$y2 = $options['y2'];

	// crop image first?
	$crop = true;
	if ($x1 == 0 && $y1 == 0 && $x2 == 0 && $y2 == 0) {
		$crop = false;
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
		if ($crop == true && $selection_width != $selection_height) {
			return false;
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

/**
 * Delete an ElggFile file
 *
 * @param int $guid ElggFile GUID
 *
 * @return bool
 */
function file_delete($guid) {
	if ($file = get_entity($guid)) {
		if ($file->canEdit()) {
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
 *
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

	if (substr_count($mimetype, 'text/')) {
		return "document";
	}

	if (substr_count($mimetype, 'audio/')) {
		return "audio";
	}

	if (substr_count($mimetype, 'image/')) {
		return "image";
	}

	if (substr_count($mimetype, 'video/')) {
		return "video";
	}

	if (substr_count($mimetype, 'opendocument')) {
		return "document";
	}

	return "general";
}

/**
 * Delete a directory and all its contents
 *
 * @param string $directory Directory to delete
 *
 * @return bool
 */
function delete_directory($directory) {
	// sanity check: must be a directory
	if (!$handle = opendir($directory)) {
		return false;
	}

	// loop through all files
	while (($file = readdir($handle)) !== false) {
		if (in_array($file, array('.', '..'))) {
			continue;
		}

		$path = "$directory/$file";
		if (is_dir($path)) {
			// recurse down through directory
			if (!delete_directory($path)) {
				return false;
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
 * @warning This only deletes the physical files and not their entities.
 * This will result in FileExceptions being thrown.  Don't use this function.
 *
 * @warning This must be kept in sync with ElggDiskFilestore.
 *
 * @todo Remove this when all files are entities.
 *
 * @param ElggUser $user An ElggUser
 *
 * @return void
 */
function clear_user_files($user) {
	global $CONFIG;

	$dir = new Elgg_EntityDirLocator($user->guid);
	$file_path = $CONFIG->dataroot . $dir;
	if (file_exists($file_path)) {
		delete_directory($file_path);
	}
}


/// Variable holding the default datastore
$DEFAULT_FILE_STORE = null;

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
 *
 * @param ElggFilestore $filestore An ElggFilestore object.
 *
 * @return true
 */
function set_default_filestore(ElggFilestore $filestore) {
	global $DEFAULT_FILE_STORE;

	$DEFAULT_FILE_STORE = $filestore;

	return true;
}

/**
 * Initialize the file library.
 * Listens to system init and configures the default filestore
 *
 * @return void
 * @access private
 */
function _elgg_filestore_init() {
	global $CONFIG;

	// Now register a default filestore
	if (isset($CONFIG->dataroot)) {
		set_default_filestore(new ElggDiskFilestore($CONFIG->dataroot));
	}

	// Unit testing
	elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_filestore_test');
}

/**
 * Unit tests for files
 *
 * @param string $hook   unit_test
 * @param string $type   system
 * @param mixed  $value  Array of tests
 *
 * @return array
 * @access private
 */
function _elgg_filestore_test($hook, $type, $value) {
	global $CONFIG;
	$value[] = "{$CONFIG->path}engine/tests/ElggCoreFilestoreTest.php";
	return $value;
}

elgg_register_event_handler('init', 'system', '_elgg_filestore_init', 100);
