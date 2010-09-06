<?php
/**
 * Elgg filestore.
 * This file contains classes, interfaces and functions for saving and retrieving data to various file
 * stores.
 *
 * @package Elgg
 * @subpackage API
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

include_once("objects.php");

require_once dirname(dirname(__FILE__)).'/classes/ElggFilestore.php';
require_once dirname(dirname(__FILE__)).'/classes/ElggDiskFilestore.php';
require_once dirname(dirname(__FILE__)).'/classes/ElggFile.php';

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
 * @return false|mixed The contents of the resized image, or false on failure
 */
function get_resized_image_from_uploaded_file($input_name, $maxwidth, $maxheight, $square = false) {
	// If our file exists ...
	if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {
		return get_resized_image_from_existing_file($_FILES[$input_name]['tmp_name'], $maxwidth, $maxheight, $square);
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
		$new_height = $maxwidth;

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

	// check for upscaling
	if (!$upscale && ($height < $new_height || $width < $new_width)) {
		// determine if we can scale it down at all
		// (ie, if only one dimension is too small)
		// if not, just use original size.
		if ($height < $new_height && $width < $new_width) {
			$ratio = 1;
		} elseif ($height < $new_height) {
			$ratio = $new_width / $width;
		} elseif ($width < $new_width) {
			$ratio = $new_height / $height;
		}
		
		$selection_height = $height;
		$selection_width = $width;
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
