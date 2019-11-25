<?php
/**
 * Group file module
 */

elgg_deprecated_notice("The view 'file/group_module' was deprecated,
	please rely on the magic group tool views in 'groups/profile/module/<tool_name>'", '3.1');

$group = elgg_extract('entity', $vars);
if (!$group instanceof \ElggGroup) {
	return;
}

if (!$group->isToolEnabled('file')) {
	return;
}

echo elgg_view('groups/profile/module/file', $vars);
