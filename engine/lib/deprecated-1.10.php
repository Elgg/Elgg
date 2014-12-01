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
 * Returns the \Elgg\Access object.
 *
 * @return \Elgg\Access
 * @since 1.7.0
 * @access private
 * @deprecated 1.10 Use elgg_get_ignore_access or elgg_set_ignore_access
 */
function elgg_get_access_object() {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_get/set_ignore_access()', '1.10');
	return _elgg_services()->access;
}
