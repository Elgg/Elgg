<?php

/**
 * Get the language set by the user, by the system, or false if no language is set.
 *
 * @return string The language code or false if not set
 * @deprecated 1.10 Use get_current_language()
 */
function get_language() {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use get_current_language()', '1.10');
	return get_current_language(false);
}
