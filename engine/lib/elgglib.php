<?php
/**
 * Bootstrapping and helper procedural code available for use in Elgg core and plugins.
 */

/**
 * Get a reference to the public service provider
 *
 * @return \Elgg\Di\PublicContainer
 * @since 2.0.0
 */
function elgg(): \Elgg\Di\PublicContainer {
	return \Elgg\Application::$_instance->public_services;
}

/**
 * Set a response HTTP header
 *
 * @see header()
 *
 * @param string $header  Header
 * @param bool   $replace Replace existing header
 * @return void
 * @since 2.3
 */
function elgg_set_http_header(string $header, bool $replace = true): void {
	if (!preg_match('~^HTTP/\\d\\.\\d~', $header)) {
		list($name, $value) = explode(':', $header, 2);
		_elgg_services()->responseFactory->setHeader($name, trim($value), $replace);
	}
}

/**
 * Registers a success system message
 *
 * @param string|array $options a single string or an array of system message options
 *
 * @see \ElggSystemMessage::factory()
 *
 * @return void
 * @since 4.2
 */
function elgg_register_success_message(string|array $options): void {
	if (!is_array($options)) {
		$options = ['message' => $options];
	}
	
	$options['type'] = 'success';
	_elgg_services()->system_messages->addMessage($options);
}

/**
 * Registers a error system message
 *
 * @param string|array $options a single string or an array of system message options
 *
 * @see \ElggSystemMessage::factory()
 *
 * @return void
 * @since 4.2
 */
function elgg_register_error_message(string|array $options): void {
	if (!is_array($options)) {
		$options = ['message' => $options];
	}
	
	$options['type'] = 'error';
	_elgg_services()->system_messages->addMessage($options);
}

/**
 * Log a message.
 *
 * If $level is >= to the debug setting in {@link $CONFIG->debug}, the
 * message will be sent to {@link elgg_dump()}.  Messages with lower
 * priority than {@link $CONFIG->debug} are ignored.
 *
 * @note Use the developers plugin to display logs
 *
 * @param string $message User message
 * @param string $level   NOTICE | WARNING | ERROR
 *
 * @return void
 * @since 1.7.0
 */
function elgg_log($message, $level = \Psr\Log\LogLevel::NOTICE): void {
	_elgg_services()->logger->log($level, $message);
}

/**
 * Logs $value to PHP's {@link error_log()}
 *
 * A 'debug', log' event is triggered. If a handler returns
 * false, it will stop the default logging method.
 *
 * @note Use the developers plugin to display logs
 *
 * @param mixed $value The value
 * @return void
 * @since 1.7.0
 */
function elgg_dump($value): void {
	_elgg_services()->logger->dump($value);
}

/**
 * Log a notice about deprecated use of a function, view, etc.
 *
 * @param string $msg         Message to log
 * @param string $dep_version Human-readable *release* version: 1.7, 1.8, ...
 *
 * @return void
 * @since 1.7.0
 */
function elgg_deprecated_notice(string $msg, string $dep_version): void {
	_elgg_services()->logger->warning("Deprecated in {$dep_version}: {$msg}");
}

/**
 * Builds a URL from the a parts array like one returned by {@link parse_url()}.
 *
 * @note If only partial information is passed, a partial URL will be returned.
 *
 * @param array $parts       Associative array of URL components like parse_url() returns
 *                           'user' and 'pass' parts are ignored because of security reasons
 * @param bool  $html_encode HTML Encode the url?
 *
 * @return string Full URL
 * @since 1.7.0
 */
function elgg_http_build_url(array $parts, bool $html_encode = true): string {
	return _elgg_services()->urls->buildUrl($parts, $html_encode);
}

/**
 * Adds action tokens to URL
 *
 * As of 1.7.0 action tokens are required on all actions.
 * Use this function to append action tokens to a URL's GET parameters.
 * This will preserve any existing GET parameters.
 *
 * @note If you are using {@elgg_view input/form} you don't need to
 * add tokens to the action.  The form view automatically handles
 * tokens.
 *
 * @param string $url         Full action URL
 * @param bool   $html_encode HTML encode the url? (default: false)
 *
 * @return string URL with action tokens
 * @since 1.7.0
 */
function elgg_add_action_tokens_to_url(string $url, bool $html_encode = false): string {
	return _elgg_services()->urls->addActionTokensToUrl($url, $html_encode);
}

/**
 * Removes an element from a URL's query string.
 *
 * @note You can send a partial URL string.
 *
 * @param string $url     Full URL
 * @param string $element The element to remove
 *
 * @return string The new URL with the query element removed.
 * @since 1.7.0
 */
function elgg_http_remove_url_query_element(string $url, string $element): string {
	return _elgg_services()->urls->addQueryElementsToUrl($url, [$element => null]);
}

/**
 * Sets elements in a URL's query string.
 *
 * @param string $url      The URL
 * @param array  $elements Key/value pairs to set in the URL. If the value is null, the
 *                         element is removed from the URL.
 *
 * @return string The new URL with the query strings added
 * @since 1.7.0
 */
function elgg_http_add_url_query_elements(string $url, array $elements): string {
	return _elgg_services()->urls->addQueryElementsToUrl($url, $elements);
}

/**
 * Test if two URLs are functionally identical.
 *
 * @tip If $ignore_params is used, neither the name nor its value will be considered when comparing.
 *
 * @tip The order of GET params doesn't matter.
 *
 * @param string $url1          First URL
 * @param string $url2          Second URL
 * @param array  $ignore_params GET params to ignore in the comparison
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_http_url_is_identical(string $url1, string $url2, array $ignore_params = ['offset', 'limit']): bool {
	return _elgg_services()->urls->isUrlIdentical($url1, $url2, (array) $ignore_params);
}

/**
 * Signs provided URL with a SHA256 HMAC key
 *
 * @note Signed URLs do not offer CSRF protection and should not be used instead of action tokens.
 *
 * @param string $url     URL to sign
 * @param string $expires Expiration time
 *                        A string suitable for strtotime()
 *                        Null value indicate non-expiring URL
 * @return string
 */
function elgg_http_get_signed_url(string $url, string $expires = null): string {
	return _elgg_services()->urlSigner->sign($url, $expires);
}

/**
 * Validates if the HMAC signature of the URL is valid
 *
 * @param string $url URL to validate
 * @return bool
 */
function elgg_http_validate_signed_url(string $url): bool {
	return _elgg_services()->urlSigner->isValid($url);
}

/**
 * Returns a Guzzle HTTP client
 *
 * @param array $options Options for the client
 *
 * @return \Elgg\Http\Client
 */
function elgg_get_http_client(array $options = []): \Elgg\Http\Client {
	return new \Elgg\Http\Client($options);
}

/**
 * Checks for $array[$key] and returns its value if it exists, else
 * returns $default.
 *
 * Shorthand for $value = (isset($array['key'])) ? $array['key'] : 'default';
 *
 * @param string|int $key     Key to check in the source array
 * @param array      $array   Source array
 * @param mixed      $default Value to return if key is not found
 * @param bool       $strict  Return array key if it's set, even if empty. If false,
 *                            return $default if the array key is unset or empty.
 *
 * @return mixed
 * @since 1.8.0
 */
function elgg_extract($key, $array, $default = null, bool $strict = true) {
	if (!is_array($array) && !$array instanceof ArrayAccess) {
		return $default;
	}

	if ($strict) {
		return $array[$key] ?? $default;
	}
	
	return (isset($array[$key]) && !empty($array[$key])) ? $array[$key] : $default;
}

/**
 * Extract class names from an array, optionally merging into a preexisting set.
 *
 * @param array           $array       Source array
 * @param string|string[] $existing    Existing name(s)
 * @param string          $extract_key Key to extract new classes from
 * @return string[]
 *
 * @since 2.3.0
 */
function elgg_extract_class(array $array, $existing = [], $extract_key = 'class'): array {
	$existing = empty($existing) ? [] : (array) $existing;

	$merge = (array) elgg_extract($extract_key, $array, []);

	array_splice($existing, count($existing), 0, $merge);

	return array_values(array_unique($existing));
}

/**
 * Calls a callable autowiring the arguments using public DI services
 * and applying logic based on flags
 *
 * @param int     $flags   Bitwise flags
 *                         ELGG_IGNORE_ACCESS
 *                         ELGG_ENFORCE_ACCESS
 *                         ELGG_SHOW_DISABLED_ENTITIES
 *                         ELGG_HIDE_DISABLED_ENTITIES
 * @param Closure $closure Callable to call
 *
 * @return mixed
 */
function elgg_call(int $flags, Closure $closure) {
	return _elgg_services()->invoker->call($flags, $closure);
}

/**
 * Returns a PHP INI setting in bytes.
 *
 * @tip Use this for arithmetic when determining if a file can be uploaded.
 *
 * @param string $setting The php.ini setting
 *
 * @return int
 * @since 1.7.0
 * @link http://www.php.net/manual/en/function.ini-get.php
 */
function elgg_get_ini_setting_in_bytes(string $setting): int {
	// retrieve INI setting
	$val = ini_get($setting);

	// convert INI setting when shorthand notation is used
	$last = strtolower($val[strlen($val) - 1]);
	if (in_array($last, ['g', 'm', 'k'])) {
		$val = substr($val, 0, -1);
	}
	
	$val = (int) $val;
	switch ($last) {
		case 'g':
			$val *= 1024;
			// fallthrough intentional
		case 'm':
			$val *= 1024;
			// fallthrough intentional
		case 'k':
			$val *= 1024;
	}

	// return byte value
	return $val;
}

/**
 * Get the global service provider
 *
 * @return \Elgg\Di\InternalContainer
 * @internal
 */
function _elgg_services(): \Elgg\Di\InternalContainer {
	// This yields a more shallow stack depth in recursive APIs like views. This aids in debugging and
	// reduces false positives in xdebug's infinite recursion protection.
	return \Elgg\Application::$_instance->internal_services;
}
