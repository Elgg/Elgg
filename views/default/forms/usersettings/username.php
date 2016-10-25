<?php

$user = elgg_extract('entity', $vars);
if (!($user instanceof ElggUser)) {
	return;
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $user->guid,
]);

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('username'),
	'#help' => elgg_echo('user:username:help'),
	'name' => 'username',
	'required' => true,
	'value' => $user->username,
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);
elgg_set_form_footer($footer);
