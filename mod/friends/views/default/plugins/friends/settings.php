<?php

/* @var $plugin \ElggPlugin */
$plugin = elgg_extract('entity', $vars);

// friend request
echo elgg_view('output/longtext', [
	'value' => elgg_echo('friends:settings:request:description'),
]);

echo elgg_view_field([
	'#type' => 'checkbox',
	'#label' => elgg_echo('friends:settings:request:label'),
	'#help' => elgg_echo('friends:settings:request:help'),
	'name' => 'params[friend_request]',
	'value' => 1,
	'checked' => (bool) $plugin->friend_request,
	'switch' => true,
]);
