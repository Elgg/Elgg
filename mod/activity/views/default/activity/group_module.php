<?php
/**
 * Groups latest activity
 */

$group = elgg_extract('entity', $vars);
if (!$group instanceof \ElggGroup) {
	return;
}

if (!$group->isToolEnabled('activity')) {
	return;
}

elgg_deprecated_notice("The view 'activity/group_module' was deprecated,
	please rely on the magic group tool views in 'groups/profile/module/<tool_name>'", '3.0');

echo elgg_view('groups/profile/module/activity', $vars);
