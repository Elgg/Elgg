<?php
/**
 * Elgg bookmark view
 *
 * @uses $vars['entity'] ElggBookmark to show
 *
 * @deprecated 6.3 use "object/bookmarks/full" or "object/bookmarks/summary"
 */

$entity = elgg_extract('entity', $vars, false);
if (!$entity instanceof \ElggBookmark) {
	return;
}

elgg_deprecated_notice('The view "object/bookmarks" has been deprecated, use "object/bookmarks/full" or "object/bookmarks/summary"', '6.3');

if (elgg_extract('full_view', $vars)) {
	echo elgg_view('object/bookmarks/full', $vars);
} else {
	echo elgg_view('object/bookmarks/summary', $vars);
}
