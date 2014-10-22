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
