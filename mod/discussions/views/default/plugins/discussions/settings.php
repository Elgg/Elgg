<?php

$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('discussion:settings:enable_global_discussions'),
	'#help' => elgg_echo('discussion:settings:enable_global_discussions:help'),
	'name' => 'params[enable_global_discussions]',
	'value' => $plugin->enable_global_discussions,
]);
