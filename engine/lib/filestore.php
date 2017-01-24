<?php

/**
 * Elgg filestore.
 * This file contains functions for saving and retrieving data from files.
 *
 * @package Elgg.Core
 * @subpackage DataModel.FileStorage
 */
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Get the size of the specified directory.
 *
 * @param string $dir        The full path of the directory
 * @param int    $total_size Add to current dir size
 *
 * @return int The size of the directory in bytes
 */
function get_dir_size($dir, $total_size = 0) {
	$handle = @opendir($dir);
	while ($file = @readdir($handle)) {
		if (in_array($file, array('.', '..'))) {
			continue;
		}
		if (is_dir($dir . $file)) {
			$total_size = get_dir_size($dir . $file . "/", $total_size);
		} else {
			$total_size += filesize($dir . $file);
		}
	}
	@closedir($handle);

	return($total_size);
}

/**
 * Get the contents of an uploaded file.
 * (Returns false if there was an issue.)
 *
 * @param string $input_name The name of the file input field on the submission form
 *
 * @return mixed|false The contents of the file, or false on failure.
 * @deprecated 2.3
 */
function get_uploaded_file($input_name) {
	elgg_deprecated_notice(__FUNCTION__ . ' has been deprecated and will be removed', '2.3');
	$inputs = elgg_get_uploaded_files($input_name);
	$input = array_shift($inputs);
	if (!$input || !$input->isValid()) {
		return false;
	}
	return file_get_contents($input->getPathname());
}

/**
 * Crops and resizes an image
 *
 * @param string $source      Path to source image
 * @param string $destination Path to destination
 *                            If not set, will modify the source image
 * @param array  $params      An array of cropping/resizing parameters
 *                             - INT 'w' represents the width of the new image
 *                               With upscaling disabled, this is the maximum width
 *                               of the new image (in case the source image is
 *                               smaller than the expected width)
 *                             - INT 'h' represents the height of the new image
 *                               With upscaling disabled, this is the maximum height
 *                             - INT 'x1', 'y1', 'x2', 'y2' represent optional cropping
 *                               coordinates. The source image will first be cropped
 *                               to these coordinates, and then resized to match
 *                               width/height parameters
 *                             - BOOL 'square' - square images will fill the
 *                               bounding box (width x height). In Imagine's terms,
 *                               this equates to OUTBOUND mode
 *                             - BOOL 'upscale' - if enabled, smaller images
 *                               will be upscaled to fit the bounding box.
 * @return bool
 * @since 2.3
 */
function elgg_save_resized_image($source, $destination = null, array $params = array()) {
	return _elgg_services()->imageService->resize($source, $destination, $params);
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
 * @deprecated 2.3
 */
function get_resized_image_from_uploaded_file($input_name, $maxwidth, $maxheight,
		$square = false, $upscale = false) {

	elgg_deprecated_notice(__FUNCTION__ . ' has been deprecated. Use elgg_save_resized_image()', '2.3');

	$files = _elgg_services()->request->files;
	if (!$files->has($input_name)) {
		return false;
	}

	$file = $files->get($input_name);
	if (empty($file)) {
		// a file input was provided but no file uploaded
		return false;
	}
	if ($file->getError() !== 0) {
		return false;
	}

	return get_resized_image_from_existing_file($file->getPathname(), $maxwidth, $maxheight, $square, 0, 0, 0, 0, $upscale);
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
 * @deprecated 2.3
 */
function get_resized_image_from_existing_file($input_name, $maxwidth, $maxheight,
			$square = false, $x1 = 0, $y1 = 0, $x2 = 0, $y2 = 0, $upscale = false) {

	elgg_deprecated_notice(__FUNCTION__ . ' has been deprecated. Use elgg_save_resized_image()', '2.3');

	if (!is_readable($input_name)) {
		return false;
	}

	// we will write resized image to a temporary file and then delete it
	// need to add a valid image extension otherwise resizing fails
	$tmp_filename = tempnam(sys_get_temp_dir(), 'icon_resize');
	
	$params = [
		'w' => $maxwidth,
		'h' => $maxheight,
		'x1' => $x1,
		'y1' => $y1,
		'x2' => $x2,
		'y2' => $y2,
		'square' => $square,
		'upscale' => $upscale,
	];

	$image_bytes = false;
	if (elgg_save_resized_image($input_name, $tmp_filename, $params)) {
		$image_bytes = file_get_contents($tmp_filename);
	}

	unlink($tmp_filename);

	return $image_bytes;
}

/**
 * Calculate the parameters for resizing an image
 *
 * @param int   $width  Natural width of the image
 * @param int   $height Natural height of the image
 * @param array $params Resize parameters
 *                      - 'maxwidth' maximum width of the resized image
 *                      - 'maxheight' maximum height of the resized image
 *                      - 'upscale' allow upscaling
 *                      - 'square' constrain to a square
 *                      - 'x1', 'y1', 'x2', 'y2' cropping coordinates
 *
 * @return array|false
 * @since 1.7.2
 * @deprecated 2.3
 */
function get_image_resize_parameters($width, $height, array $params = []) {

	elgg_deprecated_notice(__FUNCTION__ . ' has been deprecated and will be removed from public API', '2.3');

	try {
		$params['w'] = elgg_extract('maxwidth', $params);
		$params['h'] = elgg_extract('maxheight', $params);
		unset($params['maxwidth']);
		unset($params['maxheight']);
		$params = _elgg_services()->imageService->normalizeResizeParameters($width, $height, $params);
		return [
			'newwidth' => $params['w'],
			'newheight' => $params['h'],
			'selectionwidth' => $params['x2'] - $params['x1'],
			'selectionheight' => $params['y2'] - $params['y1'],
			'xoffset' => $params['x1'],
			'yoffset' => $params['y1'],
		];
	} catch (\LogicException $ex) {
		elgg_log($ex->getMessage(), 'ERROR');
		return false;
	}
}

/**
 * Delete an \ElggFile file
 *
 * @param int $guid \ElggFile GUID
 *
 * @return bool
 */
function file_delete($guid) {
	$file = get_entity($guid);
	if (!$file || !$file->canEdit()) {
		return false;
	}

	$thumbnail = $file->thumbnail;
	$smallthumb = $file->smallthumb;
	$largethumb = $file->largethumb;
	if ($thumbnail) {
		$delfile = new \ElggFile();
		$delfile->owner_guid = $file->owner_guid;
		$delfile->setFilename($thumbnail);
		$delfile->delete();
	}
	if ($smallthumb) {
		$delfile = new \ElggFile();
		$delfile->owner_guid = $file->owner_guid;
		$delfile->setFilename($smallthumb);
		$delfile->delete();
	}
	if ($largethumb) {
		$delfile = new \ElggFile();
		$delfile->owner_guid = $file->owner_guid;
		$delfile->setFilename($largethumb);
		$delfile->delete();
	}

	return $file->delete();
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
 * Removes all entity files
 *
 * @warning This only deletes the physical files and not their entities.
 * This will result in FileExceptions being thrown.  Don't use this function.
 *
 * @warning This must be kept in sync with \ElggDiskFilestore.
 *
 * @todo Remove this when all files are entities.
 *
 * @param \ElggEntity $entity An \ElggEntity
 *
 * @return void
 * @access private
 */
function _elgg_clear_entity_files($entity) {
	$dir = new \Elgg\EntityDirLocator($entity->guid);
	$file_path = elgg_get_config('dataroot') . $dir;
	if (file_exists($file_path)) {
		delete_directory($file_path);
	}
}

/// Variable holding the default datastore
$DEFAULT_FILE_STORE = null;

/**
 * Return the default filestore.
 *
 * @return \ElggFilestore
 * @deprecated Will be removed in 3.0
 */
function get_default_filestore() {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated.', '2.1');

	return $GLOBALS['DEFAULT_FILE_STORE'];
}

/**
 * Set the default filestore for the system.
 *
 * @param \ElggFilestore $filestore An \ElggFilestore object.
 *
 * @return true
 * @deprecated Will be removed in 3.0
 */
function set_default_filestore(\ElggFilestore $filestore) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated.', '2.1');

	$GLOBALS['DEFAULT_FILE_STORE'] = $filestore;
	return true;
}

/**
 * Returns the category of a file from its MIME type
 *
 * @param string $mime_type The MIME type
 *
 * @return string 'document', 'audio', 'video', or 'general' if the MIME type was unrecognized
 * @since 1.10
 */
function elgg_get_file_simple_type($mime_type) {
	$params = array('mime_type' => $mime_type);
	return elgg_trigger_plugin_hook('simple_type', 'file', $params, 'general');
}

/**
 * Bootstraps the default filestore at "boot, system" event
 *
 * @return void
 * @access private
 */
function _elgg_filestore_boot() {
	global $CONFIG;

	// Now register a default filestore
	if (isset($CONFIG->dataroot)) {
		$GLOBALS['DEFAULT_FILE_STORE'] = new \ElggDiskFilestore($CONFIG->dataroot);
	}
}

/**
 * Register file-related handlers on "init, system" event
 *
 * @return void
 * @access private
 */
function _elgg_filestore_init() {

	// Fix MIME type detection for Microsoft zipped formats
	elgg_register_plugin_hook_handler('mime_type', 'file', '_elgg_filestore_detect_mimetype');

	// Parse category of file from MIME type
	elgg_register_plugin_hook_handler('simple_type', 'file', '_elgg_filestore_parse_simpletype');

	// Unit testing
	elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_filestore_test');

	// Handler for serving embedded icons
	elgg_register_page_handler('serve-icon', '_elgg_filestore_serve_icon_handler');

	// Touch entity icons if entity access id has changed
	elgg_register_event_handler('update:after', 'object', '_elgg_filestore_touch_icons');
	elgg_register_event_handler('update:after', 'group', '_elgg_filestore_touch_icons');

	// Move entity icons if entity owner has changed
	elgg_register_event_handler('update:after', 'object', '_elgg_filestore_move_icons');
	elgg_register_event_handler('update:after', 'group', '_elgg_filestore_move_icons');
}

/**
 * Fix MIME type detection for Microsoft zipped formats
 *
 * @param string $hook      "mime_type"
 * @param string $type      "file"
 * @param string $mime_type Detected MIME type
 * @param array  $params    Hook parameters
 *
 * @return string The MIME type
 * @access private
 */
function _elgg_filestore_detect_mimetype($hook, $type, $mime_type, $params) {

	$original_filename = elgg_extract('original_filename', $params);
	$ext = pathinfo($original_filename, PATHINFO_EXTENSION);

	return (new \Elgg\Filesystem\MimeTypeDetector())->fixDetectionErrors($mime_type, $ext);
}

/**
 * Parse a file category of file from a MIME type
 *
 * @param string $hook        "simple_type"
 * @param string $type        "file"
 * @param string $simple_type The category of file
 * @param array  $params      Hook parameters
 *
 * @return string 'document', 'audio', 'video', or 'general' if the MIME type is unrecognized
 * @access private
 */
function _elgg_filestore_parse_simpletype($hook, $type, $simple_type, $params) {

	$mime_type = elgg_extract('mime_type', $params);

	switch ($mime_type) {
		case "application/msword":
		case "application/vnd.openxmlformats-officedocument.wordprocessingml.document":
		case "application/pdf":
			return "document";

		case "application/ogg":
			return "audio";
	}

	if (preg_match('~^(audio|image|video)/~', $mime_type, $m)) {
		return $m[1];
	}
	if (0 === strpos($mime_type, 'text/') || false !== strpos($mime_type, 'opendocument')) {
		return "document";
	}

	// unrecognized MIME
	return $simple_type;
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

/**
 * Returns file's download URL
 *
 * @note This does not work for files with custom filestores.
 *
 * @param \ElggFile $file       File object or entity (must have the default filestore)
 * @param bool      $use_cookie Limit URL validity to current session only
 * @param string    $expires    URL expiration, as a string suitable for strtotime()
 * @return string
 */
function elgg_get_download_url(\ElggFile $file, $use_cookie = true, $expires = '+2 hours') {
	$file_svc = new Elgg\FileService\File();
	$file_svc->setFile($file);
	$file_svc->setExpires($expires);
	$file_svc->setDisposition('attachment');
	$file_svc->bindSession($use_cookie);
	return $file_svc->getURL();
}

/**
 * Returns file's URL for inline display
 * Suitable for displaying cacheable resources, such as user avatars
 *
 * @note This does not work for files with custom filestores.
 *
 * @param \ElggFile $file       File object or entity (must have the default filestore)
 * @param bool      $use_cookie Limit URL validity to current session only
 * @param string    $expires    URL expiration, as a string suitable for strtotime()
 * @return string
 */
function elgg_get_inline_url(\ElggFile $file, $use_cookie = false, $expires = '') {
	$file_svc = new Elgg\FileService\File();
	$file_svc->setFile($file);
	if ($expires) {
		$file_svc->setExpires($expires);
	}
	$file_svc->setDisposition('inline');
	$file_svc->bindSession($use_cookie);
	return $file_svc->getURL();
}

/**
 * Returns a URL suitable for embedding entity's icon in a text editor.
 * We can not use elgg_get_inline_url() for these purposes due to a URL structure
 * bound to user session and file modification time.
 * This function returns a generic (permanent) URL that will then be resolved to
 * an inline URL whenever requested.
 *
 * @param \ElggEntity $entity Entity
 * @param string      $size   Size
 * @return string
 * @since 2.2
 */
function elgg_get_embed_url(\ElggEntity $entity, $size) {
	return elgg_normalize_url("serve-icon/$entity->guid/$size");
}

/**
 * Handler for /serve-icon resources
 * /serve-icon/<entity_guid>/<size>
 *
 * @return void
 * @access private
 * @since 2.2
 */
function _elgg_filestore_serve_icon_handler() {
	$response = _elgg_services()->iconService->handleServeIconRequest();
	$response->send();
	exit;
}

/**
 * Reset icon URLs if access_id has changed
 *
 * @param string     $event  "update:after"
 * @param string     $type   "object"|"group"
 * @param ElggObject $entity Entity
 * @return void
 * @access private
 */
function _elgg_filestore_touch_icons($event, $type, $entity) {
	$original_attributes = $entity->getOriginalAttributes();
	if (!array_key_exists('access_id', $original_attributes)) {
		return;
	}
	if ($entity instanceof \ElggFile) {
		// we touch the file to invalidate any previously generated download URLs
		$entity->setModifiedTime();
	}
	$sizes = array_keys(elgg_get_icon_sizes($entity->getType(), $entity->getSubtype()));
	foreach ($sizes as $size) {
		$icon = $entity->getIcon($size);
		if ($icon->exists()) {
			$icon->setModifiedTime();
		}
	}
}

/**
 * Listen to entity ownership changes and update icon ownership by moving
 * icons to their new owner's directory on filestore.
 *
 * This will only transfer icons that have a custom location on filestore
 * and are owned by the entity's owner (instead of the entity itself).
 * Even though core icon service does not store icons in the entity's owner
 * directory, there are plugins that do (e.g. file plugin) - this handler
 * helps such plugins avoid ownership mismatch.
 *
 * @param string     $event  "update:after"
 * @param string     $type   "object"|"group"
 * @param ElggObject $entity Entity
 * @return void
 * @access private
 */
function _elgg_filestore_move_icons($event, $type, $entity) {

	$original_attributes = $entity->getOriginalAttributes();
	if (empty($original_attributes['owner_guid'])) {
		return;
	}

	$previous_owner_guid = $original_attributes['owner_guid'];
	$new_owner_guid = $entity->owner_guid;

	$sizes = elgg_get_icon_sizes($entity->getType(), $entity->getSubtype());

	foreach ($sizes as $size => $opts) {
		$new_icon = $entity->getIcon($size);
		if ($new_icon->owner_guid == $entity->guid) {
			// we do not need to update icons that are owned by the entity itself
			continue;
		}

		if ($new_icon->owner_guid != $new_owner_guid) {
			// a plugin implements some custom logic
			continue;
		}

		$old_icon = new \ElggIcon();
		$old_icon->owner_guid = $previous_owner_guid;
		$old_icon->setFilename($new_icon->getFilename());
		if (!$old_icon->exists()) {
			// there is no icon to move
			continue;
		}

		if ($new_icon->exists()) {
			// there is already a new icon
			// just removing the old one
			$old_icon->delete();
			elgg_log("Entity $entity->guid has been transferred to a new owner but an icon was left behind under {$old_icon->getFilenameOnFilestore()}. "
			. "Old icon has been deleted", 'NOTICE');
			continue;
		}

		$old_icon->transfer($new_icon->owner_guid, $new_icon->getFilename());
		elgg_log("Entity $entity->guid has been transferred to a new owner. "
		. "Icon was moved from {$old_icon->getFilenameOnFilestore()} to {$new_icon->getFilenameOnFilestore()}.", 'NOTICE');
	}
}

/**
 * Returns an array of uploaded file objects regardless of upload status/errors
 *
 * @param string $input_name Form input name
 * @return UploadedFile[]|false
 */
function elgg_get_uploaded_files($input_name) {
	return _elgg_services()->uploads->getUploadedFiles($input_name);
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('boot', 'system', '_elgg_filestore_boot', 100);
	$events->registerHandler('init', 'system', '_elgg_filestore_init', 100);
};
