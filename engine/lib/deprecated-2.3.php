<?php

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

	$file = _elgg_services()->request->getFile($input_name);
	if (empty($file)) {
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
