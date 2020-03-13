<?php
/**
 * Elgg filestore.
 * This file contains functions for saving and retrieving data from files.
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
	if (!is_dir($dir)) {
		return $total_size;
	}
	
	$handle = opendir($dir);
	while (($file = readdir($handle)) !== false) {
		if (in_array($file, ['.', '..'])) {
			continue;
		}
		if (is_dir($dir . $file)) {
			$total_size = get_dir_size($dir . $file . "/", $total_size);
		} else {
			$total_size += filesize($dir . $file);
		}
	}
	closedir($handle);

	return($total_size);
}

/**
 * Crops and resizes an image
 *
 * The following parameters are supported in params:
 * - INT 'w' represents the width of the new image
 *   With upscaling disabled, this is the maximum width
 *   of the new image (in case the source image is
 *   smaller than the expected width)
 * - INT 'h' represents the height of the new image
 *   With upscaling disabled, this is the maximum height
 * - INT 'x1', 'y1', 'x2', 'y2' represent optional cropping
 *   coordinates. The source image will first be cropped
 *   to these coordinates, and then resized to match
 *   width/height parameters
 * - BOOL 'square' - square images will fill the
 *   bounding box (width x height). In Imagine's terms,
 *   this equates to OUTBOUND mode
 * - BOOL 'upscale' - if enabled, smaller images
 *   will be upscaled to fit the bounding box.
 *
 * @param string $source      Path to source image
 * @param string $destination Path to destination
 *                            If not set, will modify the source image
 * @param array  $params      An array of cropping/resizing parameters
 *
 * @return bool
 * @since 2.3
 */
function elgg_save_resized_image($source, $destination = null, array $params = []) {
	return _elgg_services()->imageService->resize($source, $destination, $params);
}

/**
 * Delete a directory and all its contents
 *
 * @param string $directory            Directory to delete
 * @param bool   $leave_base_directory Leave the base directory intact (default: false)
 *
 * @return bool
 *
 * @since 3.1
 */
function elgg_delete_directory(string $directory, bool $leave_base_directory = false) {

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
			if (!elgg_delete_directory($path)) {
				return false;
			}
		} else {
			// delete file
			unlink($path);
		}
	}

	// close file handler
	closedir($handle);
	
	if ($leave_base_directory) {
		return true;
	}
	
	// remove empty directory
	return rmdir($directory);
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
