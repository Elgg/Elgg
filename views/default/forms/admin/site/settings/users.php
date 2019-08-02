<?php

$result = elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('installation:registration:label'),
	'#help' => elgg_echo('installation:registration:description'),
	'name' => 'allow_registration',
	'checked' => (bool) elgg_get_config('allow_registration'),
	'switch' => true,
]);

$result .= elgg_view_field([
	'#type' => 'checkbox',
	'label' => elgg_echo('config:users:can_change_username'),
	'#help' => elgg_echo('config:users:can_change_username:help'),
	'name' => 'can_change_username',
	'checked' => (bool) elgg_get_config('can_change_username'),
	'switch' => true,
]);

echo elgg_view_module('info', elgg_echo('admin:settings:users'), $result);
