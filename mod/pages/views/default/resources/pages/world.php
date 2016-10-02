<?php
/**
 * @deprecated 2.3 Use the view resources/pages/all
 */

if (!elgg_extract('__shown_notice', $vars)) {
	elgg_deprecated_notice('The view "resources/pages/world" is deprecated. Use "resources/pages/all".', 2.3);
}

echo elgg_view('resources/pages/all', $vars);
