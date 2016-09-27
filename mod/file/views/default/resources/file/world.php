<?php
/**
 * @deprecated 2.3 Use the view resources/file/all
 */

if (!elgg_extract('__shown_notice', $vars)) {
	elgg_deprecated_notice('The view "resources/file/world" is deprecated. Use "resources/file/all".', 2.3);
}

echo elgg_view('resources/file/all', $vars);
