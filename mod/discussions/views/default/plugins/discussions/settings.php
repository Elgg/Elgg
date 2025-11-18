<?php

$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('discussion:settings:enable_global_discussions'),
	'#help' => elgg_echo('discussion:settings:enable_global_discussions:help'),
	'name' => 'params[enable_global_discussions]',
	'value' => $plugin->enable_global_discussions,
]);

echo elgg_view_field([
	'#type' => 'number',
	'#label' => elgg_echo('discussion:settings:auto_close'),
	'#help' => elgg_echo('discussion:settings:auto_close:help'),
	'name' => 'params[auto_close_days]',
	'value' => $plugin->auto_close_days,
	'min' => 0,
]);
