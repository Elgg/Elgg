<?php

$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('discussion:settings:enable_global_discussions'),
	'#help' => elgg_echo('discussion:settings:enable_global_discussions:help'),
	'name' => 'params[enable_global_discussions]',
	'value' => 1,
	'switch' => true,
	'checked' => (bool) $plugin->enable_global_discussions,
]);
