<?php
/**
 * Configure group specific discussions settings
 */

$group = elgg_extract('entity', $vars);

$checked = true;
if ($group instanceof \ElggGroup) {
	$checked = (bool) $group->getPluginSetting('discussions', 'add_group_subscribers_to_discussion_comments', true);
}

$content = elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('discussions:groups:edit:add_group_subscribers_to_discussion_comments'),
	'name' => 'settings[discussions][add_group_subscribers_to_discussion_comments]',
	'value' => $checked,
]);

echo elgg_view_module('info', elgg_echo('collection:object:discussion'), $content);
