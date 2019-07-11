<?php
/**
 * Latest forum posts
 *
 * @uses $vars['entity']
 */

elgg_deprecated_notice("The view 'discussion/group_module' was deprecated,
	please rely on the magic group tool views in 'groups/profile/module/<tool_name>'", '3.1');

$group = elgg_extract('entity', $vars);
if (!$group instanceof \ElggGroup) {
	return;
}

if (!$group->isToolEnabled('forum')) {
	return;
}

echo elgg_view('groups/profile/module/forum', $vars);
