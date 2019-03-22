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
		if (in_array($file, ['.', '..'])) {
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
function elgg_save_resized_image($source, $destination = null, array $params = []) {
	return _elgg_services()->imageService->resize($source, $destination, $params);
}

/**
 * Delete a directory and all its contents
 *
 * @param string $directory Directory to delete
 *
 * @return bool
 */
function delete_directory($directory) {

	if (!file_exists($directory)) {
		return true;
	}

	if (!is_dir($directory)) {
		return false;
	}

	// sanity check: must be a directory
	if (!$handle = opendir($directory)) {
		return false;
	}

	// loop through all files
	while (($file = readdir($handle)) !== false) {
		if (in_array($file, ['.', '..'])) {
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
 * Returns the category of a file from its MIME type
 *
 * @param string $mime_type The MIME type
 *
 * @return string 'document', 'audio', 'video', or 'general' if the MIME type was unrecognized
 * @since 1.10
 */
function elgg_get_file_simple_type($mime_type) {
	$params = ['mime_type' => $mime_type];
	return elgg_trigger_plugin_hook('simple_type', 'file', $params, 'general');
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
 * @param string $hook  'unit_test'
 * @param string $type  'system'
 * @param mixed  $value Array of tests
 *
 * @return array
 * @access private
 * @codeCoverageIgnore
 */
function _elgg_filestore_test($hook, $type, $value) {
	$value[] = ElggCoreFilestoreTest::class;
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
	return $file->getDownloadURL($use_cookie, $expires);
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
	return $file->getInlineURL($use_cookie, $expires);
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
	return elgg_normalize_url(elgg_generate_url('serve-icon', [
		'guid' => $entity->guid,
		'size' => $size,
	]));
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
			elgg_log("Entity $entity->guid has been transferred to a new owner but an icon was "
				. "left behind under {$old_icon->getFilenameOnFilestore()}. "
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
 * @return UploadedFile[]
 */
function elgg_get_uploaded_files($input_name) {
	return _elgg_services()->uploads->getFiles($input_name);
}

/**
 * Returns a single valid uploaded file object
 *
 * @param string $input_name         Form input name
 * @param bool   $check_for_validity If there is an uploaded file, is it required to be valid
 *
 * @return UploadedFile|false
 */
function elgg_get_uploaded_file($input_name, $check_for_validity = true) {
	return _elgg_services()->uploads->getFile($input_name, $check_for_validity);
}

/**
 * Returns a ElggTempFile which can handle writing/reading of data to a temporary file location
 *
 * @return ElggTempFile
 * @since 3.0
 */
function elgg_get_temp_file() {
	return new ElggTempFile();
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_filestore_init', 100);
};
