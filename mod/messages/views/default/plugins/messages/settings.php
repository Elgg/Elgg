<?php

/* @var $plugin \ElggPlugin */
$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('messages:settings:friends_only:label'),
	'#help' => elgg_echo('messages:settings:friends_only:help'),
	'name' => 'params[friends_only]',
	'value' => 1,
	'checked' => (bool) $plugin->friends_only,
	'switch' => true,
]);
