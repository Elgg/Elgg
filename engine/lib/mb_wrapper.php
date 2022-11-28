<?php
/**
 * Elgg UTF-8 string functions
 */

/**
 * Parses a string using mb_parse_str() if available.
 * NOTE: This differs from parse_str() by returning the results
 * instead of placing them in the local scope!
 *
 * @param string $str The string
 *
 * @return array
 * @since 1.7.0
 */
function elgg_parse_str($str) {
	if (is_callable('mb_parse_str')) {
		mb_parse_str($str, $results);
	} else {
		parse_str($str, $results);
	}

	return $results;
}

/**
 * Wrapper function for mb_stristr(). Falls back to stristr() if
 * mb_stristr() isn't available.  Parameters are passed to the
 * wrapped function in the same order they are passed to this
 * function.
 *
 * @return false|string
 * @since 1.7.0
 */
function elgg_stristr() {
	$args = func_get_args();
	if (is_callable('mb_stristr')) {
		return call_user_func_array('mb_stristr', $args);
	}
	return call_user_func_array('stristr', $args);
}

/**
 * Wrapper function for mb_strlen(). Falls back to strlen() if
 * mb_strlen() isn't available.  Parameters are passed to the
 * wrapped function in the same order they are passed to this
 * function.
 *
 * @return int
 * @since 1.7.0
 */
function elgg_strlen() {
	$args = func_get_args();
	if (is_callable('mb_strlen')) {
		return call_user_func_array('mb_strlen', $args);
	}
	return call_user_func_array('strlen', $args);
}

/**
 * Wrapper function for mb_strpos(). Falls back to strpos() if
 * mb_strpos() isn't available.  Parameters are passed to the
 * wrapped function in the same order they are passed to this
 * function.
 *
 * @return false|int
 * @since 1.7.0
 */
function elgg_strpos() {
	$args = func_get_args();
	if (is_callable('mb_strpos')) {
		return call_user_func_array('mb_strpos', $args);
	}
	return call_user_func_array('strpos', $args);
}

/**
 * Wrapper function for mb_strrchr(). Falls back to strrchr() if
 * mb_strrchr() isn't available.  Parameters are passed to the
 * wrapped function in the same order they are passed to this
 * function.
 *
 * @return false|string
 * @since 1.7.0
 */
function elgg_strrchr() {
	$args = func_get_args();
	if (is_callable('mb_strrchr')) {
		return call_user_func_array('mb_strrchr', $args);
	}
	return call_user_func_array('strrchr', $args);
}

/**
 * Wrapper function for mb_strripos(). Falls back to strripos() if
 * mb_strripos() isn't available.  Parameters are passed to the
 * wrapped function in the same order they are passed to this
 * function.
 *
 * @return false|int
 * @since 1.7.0
 */
function elgg_strripos() {
	$args = func_get_args();
	if (is_callable('mb_strripos')) {
		return call_user_func_array('mb_strripos', $args);
	}
	return call_user_func_array('strripos', $args);
}

/**
 * Wrapper function for mb_strrpos(). Falls back to strrpos() if
 * mb_strrpos() isn't available.  Parameters are passed to the
 * wrapped function in the same order they are passed to this
 * function.
 *
 * @return false|int
 * @since 1.7.0
 */
function elgg_strrpos() {
	$args = func_get_args();
	if (is_callable('mb_strrpos')) {
		return call_user_func_array('mb_strrpos', $args);
	}
	return call_user_func_array('strrpos', $args);
}

/**
 * Wrapper function for mb_strstr(). Falls back to strstr() if
 * mb_strstr() isn't available.  Parameters are passed to the
 * wrapped function in the same order they are passed to this
 * function.
 *
 * @return false|string
 * @since 1.7.0
 */
function elgg_strstr() {
	$args = func_get_args();
	if (is_callable('mb_strstr')) {
		return call_user_func_array('mb_strstr', $args);
	}
	return call_user_func_array('strstr', $args);
}

/**
 * Wrapper function for mb_strtolower(). Falls back to strtolower() if
 * mb_strtolower() isn't available.  Parameters are passed to the
 * wrapped function in the same order they are passed to this
 * function.
 *
 * @return string
 * @since 1.7.0
 */
function elgg_strtolower() {
	$args = func_get_args();
	if (is_callable('mb_strtolower')) {
		return call_user_func_array('mb_strtolower', $args);
	}
	return call_user_func_array('strtolower', $args);
}

/**
 * Wrapper function for mb_strtoupper(). Falls back to strtoupper() if
 * mb_strtoupper() isn't available.  Parameters are passed to the
 * wrapped function in the same order they are passed to this
 * function.
 *
 * @return string
 * @since 1.7.0
 */
function elgg_strtoupper() {
	$args = func_get_args();
	if (is_callable('mb_strtoupper')) {
		return call_user_func_array('mb_strtoupper', $args);
	}
	return call_user_func_array('strtoupper', $args);
}

/**
 * Wrapper for mb_convert_case($str, MB_CASE_TITLE)
 *
 * @param string $str String
 * @return string
 * @since 2.3
 */
function elgg_ucwords($str) {
	if (is_callable('mb_convert_case')) {
		return mb_convert_case($str, MB_CASE_TITLE, 'UTF-8');
	}
	return ucwords($str);
}

/**
 * Wrapper function for mb_substr_count(). Falls back to substr_count() if
 * mb_substr_count() isn't available.  Parameters are passed to the
 * wrapped function in the same order they are passed to this
 * function.
 *
 * @return int
 * @since 1.7.0
 */
function elgg_substr_count() {
	$args = func_get_args();
	if (is_callable('mb_substr_count')) {
		return call_user_func_array('mb_substr_count', $args);
	}
	return call_user_func_array('substr_count', $args);
}

/**
 * Wrapper function for mb_substr(). Falls back to substr() if
 * mb_substr() isn't available.  Parameters are passed to the
 * wrapped function in the same order they are passed to this
 * function.
 *
 * @return false|string
 * @since 1.7.0
 */
function elgg_substr() {
	$args = func_get_args();
	if (is_callable('mb_substr')) {
		return call_user_func_array('mb_substr', $args);
	}
	return call_user_func_array('substr', $args);
}
