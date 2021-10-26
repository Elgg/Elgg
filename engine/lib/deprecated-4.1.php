<?php
/**
 * Bundle all functions which have been deprecated in Elgg 4.1
 */

/**
 * Get the current Elgg version information
 *
 * @param bool $human_readable Whether to return a human readable version (default: false)
 *
 * @return string|false Depending on success
 * @since 1.9
 * @deprecated 4.1
 */
function elgg_get_version($human_readable = false) {
	
	if ($human_readable) {
		elgg_deprecated_notice(__METHOD__ . ' has been deprecated, use elgg_get_release()', '4.1');
		return elgg_get_release();
	}
	
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Do not rely on the version number. Instead use elgg_get_release() to get a release tag.', '4.1');
	return '2017041200';
}
