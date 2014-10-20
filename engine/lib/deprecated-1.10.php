<?php

/**
 * Returns an overall file type from the mimetype
 *
 * @param string $mimetype The MIME type
 * @return string The overall type
 * @deprecated 1.10 Use elgg_get_file_simple_type()
 */
function file_get_simple_type($mimetype) {
	elgg_deprecated_notice('Use elgg_get_file_simple_type() instead of file_get_simple_type()', '1.10');
	return elgg_get_file_simple_type($mimetype);
}

/**
 * Returns an overall file type from the mimetype
 *
 * @param string $mimetype The MIME type
 *
 * @return string The overall type
 * @deprecated 1.10 Use elgg_get_file_simple_type()
 */
function file_get_general_file_type($mimetype) {
	elgg_deprecated_notice('Use elgg_get_file_simple_type() instead of file_get_general_file_type()', '1.10');
	return elgg_get_file_simple_type($mimetype);
}

