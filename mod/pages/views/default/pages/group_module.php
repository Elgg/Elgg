<?php
/**
 * Group pages
 */

elgg_deprecated_notice("The view 'pages/group_module' was deprecated,
	please rely on the magic group tool views in 'groups/profile/module/<tool_name>'", '3.1');

$group = elgg_extract('entity', $vars);
if (!$group instanceof \ElggGroup) {
	return;
}

if (!$group->isToolEnabled('pages')) {
	return;
}

echo elgg_view('groups/profile/module/pages', $vars);
