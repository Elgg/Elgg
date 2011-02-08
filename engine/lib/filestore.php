<?php
/**
 * Elgg filestore.
 * This file contains classes, interfaces and functions for
 * saving and retrieving data to various file stores.
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
	while ($file = @readdir ($handle)) {
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

	// If our file exists ...
	if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {
		return get_resized_image_from_existing_file($_FILES[$input_name]['tmp_name'], $maxwidth,
			$maxheight, $square, 0, 0, 0, 0, $upscale);
	}

	return false;
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
function get_resized_image_from_existing_file($input_name, $maxwidth, $maxheight, $square = FALSE,
$x1 = 0, $y1 = 0, $x2 = 0, $y2 = 0, $upscale = FALSE) {

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
 * @param int   $width   Width of the original image
 * @param int   $height  Height of the original image
 * @param array $options See $defaults for the options
 *
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
 * @param str $directory Directory to delete
 *
 * @return bool
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
 * @warning This only deletes the physical files and not their entities.
 * This will result in FileExceptions being thrown.  Don't use this function.
 *
 * @param ElggUser $user And ElggUser
 *
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
 * Register entity type objects, subtype file as
 * ElggFile.
 *
 * @return void
 */
function filestore_run_once() {
	// Register a class
	add_subtype("object", "file", "ElggFile");
}

/**
 * Initialise the file modules.
 * Listens to system boot and registers any appropriate file types and classes
 *
 * @return void
 */
function filestore_init() {
	global $CONFIG;

	// Now register a default filestore
	set_default_filestore(new ElggDiskFilestore($CONFIG->dataroot));

	// Now run this stuff, but only once
	run_function_once("filestore_run_once");
}

/**
 * Unit tests for files
 *
 * @param sting  $hook   unit_test
 * @param string $type   system
 * @param mixed  $value  Array of tests
 * @param mixed  $params Params
 *
 * @return array
 */
function filestore_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = "{$CONFIG->path}engine/tests/objects/filestore.php";
	return $value;
}


// Register a startup event
elgg_register_event_handler('init', 'system', 'filestore_init', 100);

// Unit testing
elgg_register_plugin_hook_handler('unit_test', 'system', 'filestore_test');
