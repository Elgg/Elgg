<?php

/**
 * Catch calls to forward() in ajax request and force an exit.
 *
 * Forces response is json of the following form:
 * <pre>
 * {
 *     "current_url": "the.url.we/were/coming/from",
 *     "forward_url": "the.url.we/were/going/to",
 *     "system_messages": {
 *         "messages": ["msg1", "msg2", ...],
 *         "errors": ["err1", "err2", ...]
 *     },
 *     "status": -1 //or 0 for success if there are no error messages present
 * }
 * </pre>
 * where "system_messages" is all message registers at the point of forwarding
 *
 * @internal registered for the 'forward', 'all' plugin hook
 *
 * @param string $hook
 * @param string $type
 * @param string $forward_url
 * @param array  $params
 * @return void
 * @access private
 * @deprecated 2.3
 */
function ajax_forward_hook($hook, $type, $forward_url, $params) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated and is no longer used as a plugin hook handler', '2.3');
	
	if (!elgg_is_xhr()) {
		return;
	}

	// grab any data echo'd in the action
	$output = ob_get_clean();

	if ($type == 'walled_garden' || $type == 'csrf') {
		$type = '403';
	}

	$status_code = (int) $type;
	if ($status_code < 100 || ($status_code > 299 && $status_code < 400) || $status_code > 599) {
		// We only want to preserve OK and error codes
		// Redirect responses should be converted to OK responses as this is an XHR request
		$status_code = ELGG_HTTP_OK;
	}

	$response = elgg_ok_response($output, '', $forward_url, $status_code);

	$headers = $response->getHeaders();
	$headers['Content-Type'] = 'application/json; charset=UTF-8';
	$response->setHeaders($headers);

	_elgg_services()->responseFactory->respond($response);
	exit;
}

/**
 * Buffer all output echo'd directly in the action for inclusion in the returned JSON.
 * @return void
 * @access private
 * @deprecated 2.3
 */
function ajax_action_hook() {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated and is no longer used as a plugin hook handler', '2.3');
	
	if (elgg_is_xhr()) {
		ob_start();
	}
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

/**
 * Update the last_action column in the entities table for $guid.
 *
 * @warning This is different to time_updated.  Time_updated is automatically set,
 * while last_action is only set when explicitly called.
 *
 * @param int $guid   Entity annotation|relationship action carried out on
 * @param int $posted Timestamp of last action
 *
 * @return int|false Timestamp or false on failure
 * @access private
 * @deprecated 2.3
 */
function update_entity_last_action($guid, $posted = null) {
	elgg_deprecated_notice(__FUNCTION__ . ' has been deprecated. Refrain from updating last action timestamp manually', '2.3');

	$result = false;
	$ia = elgg_set_ignore_access(true);
	$entity = get_entity($guid);
	if ($entity) {
		$result = $entity->updateLastAction($posted);
	}
	elgg_set_ignore_access($ia);
	return $result;
}

/**
 * Get the notification settings for a given user.
 *
 * @param int $user_guid The user id
 *
 * @return \stdClass|false
 * @deprecated 2.3
 */
function get_user_notification_settings($user_guid = 0) {

	elgg_deprecated_notice(__FUNCTION__ . ' has been deprecated by ElggUser::getNotificationSettings()', '2.3');

	if ((int) $user_guid == 0) {
		$user_guid = elgg_get_logged_in_user_guid();
	}

	$user = get_entity($user_guid);
	if (!$user instanceof \ElggUser) {
		return false;
	}

	return (object) $user->getNotificationSettings();
}

/**
 * Set a user notification pref.
 *
 * @param int    $user_guid The user id.
 * @param string $method    The delivery method (eg. email)
 * @param bool   $value     On(true) or off(false).
 *
 * @return bool
 * @deprecated 2.3
 */
function set_user_notification_setting($user_guid, $method, $value) {
	elgg_deprecated_notice(__FUNCTION__ . ' has been deprecated by ElggUser::setNotificationSetting()', '2.3');

	if (!$user_guid) {
		$user_guid = elgg_get_logged_in_user_guid();
	}
	$user = get_entity($user_guid);
	if (!$user instanceof \ElggUser) {
		return false;
	}

	if (is_string($value)) {
		$value = strtolower($value);
	}
	if ($value == 'yes' || $value == 'on' || $value == 'enabled') {
		$value = true;
	} else if ($value == 'no' || $value == 'off' || $value == 'disabled') {
		$value = false;
	}

	return $user->setNotificationSetting($method, (bool) $value);
}

/**
 * Serve an error page
 *
 * This is registered by Elgg for the 'forward', '404' plugin hook. It can
 * registered for other hooks by plugins or called directly to display an
 * error page.
 *
 * @param string $hook   The name of the hook
 * @param string $type   Http error code
 * @param bool   $result The current value of the hook
 * @param array  $params Parameters related to the hook
 * @return void
 * @deprecated 2.3
 */
function elgg_error_page_handler($hook, $type, $result, $params) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Error pages are drawn by resource views without "forward" hook.', '2.3');
	
	// This draws an error page, and sometimes there's another 40* forward() call made during that process.
	// We want to allow the 2nd call to pass through, but draw the appropriate page for the first call.
	
	static $vars;
	if ($vars === null) {
		// keep first vars for error page
		$vars = [
			'type' => $type,
			'params' => $params,
		];
	}

	static $calls = 0;
	$calls++;
	if ($calls < 3) {
		echo elgg_view_resource('error', $vars);
		exit;
	}

	// uh oh, may be infinite loop
	register_error(elgg_echo('error:404:content'));
	header('Location: ' . elgg_get_site_url());
	exit;
}

/**
 * Renders a form field
 *
 * @param string $input_type Input type, used to generate an input view ("input/$input_type")
 * @param array  $vars       Fields and input vars.
 *                           Field vars contain both field and input params. 'label', 'help',
 *                           and 'field_class' params will not be passed on to the input view.
 *                           Others, including 'required' and 'id', will be available to the
 *                           input view. Both 'label' and 'help' params accept HTML, and
 *                           will be printed unescaped within their wrapper element.
 * @return string
 *
 * @since 2.1
 * @deprecated 2.3 Use elgg_view_field()
 */
function elgg_view_input($input_type, array $vars = []) {

	elgg_deprecated_notice(__FUNCTION__ . '() is deprecated. Use elgg_view_field()', '2.3');

	$vars['#type'] = $input_type;

	if (isset($vars['label']) && $input_type !== 'checkbox') {
		$vars['#label'] = $vars['label'];
		unset($vars['label']);
	}
	if (isset($vars['help'])) {
		$vars['#help'] = $vars['help'];
		unset($vars['help']);
	}
	if (isset($vars['field_class'])) {
		$vars['#class'] = $vars['field_class'];
		unset($vars['field_class']);
	}

	return elgg_view_field($vars);
}

/**
 * Sanitizes a string for use in a query
 *
 * @see Elgg\Database::sanitizeString
 *
 * @param string $string The string to sanitize
 * @return string
 * @deprecated Use query parameters where possible
 */
function sanitize_string($string) {
	return _elgg_services()->db->sanitizeString($string);
}

/**
 * Alias of sanitize_string
 *
 * @see Elgg\Database::sanitizeString
 *
 * @param string $string The string to sanitize
 * @return string
 * @deprecated Use query parameters where possible
 */
function sanitise_string($string) {
	return _elgg_services()->db->sanitizeString($string);
}

/**
 * Sanitizes an integer for database use.
 *
 * @see Elgg\Database::sanitizeInt
 *
 * @param int  $int    Value to be sanitized
 * @param bool $signed Whether negative values should be allowed (true)
 * @return int
 * @deprecated Use query parameters where possible
 */
function sanitize_int($int, $signed = true) {
	return _elgg_services()->db->sanitizeInt($int, $signed);
}

/**
 * Alias of sanitize_int
 *
 * @see sanitize_int
 *
 * @param int  $int    Value to be sanitized
 * @param bool $signed Whether negative values should be allowed (true)
 * @return int
 * @deprecated Use query parameters where possible
 */
function sanitise_int($int, $signed = true) {
	return sanitize_int($int, $signed);
}
