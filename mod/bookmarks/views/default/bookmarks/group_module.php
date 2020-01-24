<?php
/**
 * List most recent bookmarks on group profile page
 */

elgg_deprecated_notice("The view 'bookmarks/group_module' was deprecated,
	please rely on the magic group tool views in 'groups/profile/module/<tool_name>'", '3.1');

$group = elgg_extract('entity', $vars);
if (!$group instanceof \ElggGroup) {
	return;
}

if (!$group->isToolEnabled('bookmarks')) {
	return;
}

echo elgg_view('groups/profile/module/bookmarks', $vars);
