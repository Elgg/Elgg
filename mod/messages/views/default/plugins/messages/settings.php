<?php

/* @var $plugin \ElggPlugin */
$plugin = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'switch',
	'#label' => elgg_echo('messages:settings:friends_only:label'),
	'#help' => elgg_echo('messages:settings:friends_only:help'),
	'name' => 'params[friends_only]',
	'value' => $plugin->friends_only,
]);
