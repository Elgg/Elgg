<?php

/**
 * Escapes HTML in URLs
 *
 * @param string $url The URL
 *
 * @return string
 * @since 1.7.1
 * @deprecated 2.3 Use elgg_format_element() or the "output/text" view for HTML escaping
 */
function elgg_format_url($url) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_format_element() or the '
		. '"output/text" view for escaping', '2.3');

	return htmlspecialchars($url, ENT_QUOTES, 'UTF-8', false);
}
