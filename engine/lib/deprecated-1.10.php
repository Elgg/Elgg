<?php

/**
 * Returns the category of a file from its MIME type
 *
 * @param string $mime_type The MIME type
 *
 * @return string 'document', 'audio', 'video', or 'general' if the MIME type is unrecognized
 * @deprecated 1.10 Use elgg_get_file_simple_type()
 */
function file_get_simple_type($mime_type) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_get_file_simple_type()', '1.10');
	return elgg_get_file_simple_type($mime_type);
}

/**
 * Returns the category of a file from its MIME type
 *
 * @param string $mime_type The MIME type
 *
 * @return string 'document', 'audio', 'video', or 'general' if the MIME type is unrecognized
 * @deprecated 1.10 Use elgg_get_file_simple_type()
 */
function file_get_general_file_type($mime_type) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_get_file_simple_type()', '1.10');
	return elgg_get_file_simple_type($mime_type);
}

/**
 * Returns an object with methods set_ignore_access() and get_ignore_access() for back compatibility.
 *
 * Note: This no longer promises to return an instance of ElggAccess or Elgg\Access.
 *
 * @return \ElggSession
 * @since 1.7.0
 * @access private
 * @deprecated 1.10 Use elgg_get_ignore_access or elgg_set_ignore_access
 */
function elgg_get_access_object() {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_get/set_ignore_access()', '1.8');

	return _elgg_services()->session;
}

/**
 * Create a legacy password hash (salted MD5).
 *
 * @param \ElggUser $user     The user this is being generated for.
 * @param string    $password Password in clear text
 *
 * @return string
 * @access private
 * @deprecated 1.10.0 The password hashing API is not public
 */
function generate_user_password(\ElggUser $user, $password) {
	elgg_deprecated_notice(__FUNCTION__ . " is deprecated and will not be replaced.", "1.10");
	return _elgg_services()->passwords->generateLegacyHash($user, $password);
}
