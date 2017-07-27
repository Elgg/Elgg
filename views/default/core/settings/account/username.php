<?php

if (!elgg_is_admin_logged_in()) {
	// only admins are allowed to change the username
	return;
}

$user = elgg_get_page_owner_entity();
if (!($user instanceof ElggUser)) {
	return;
}

$body = elgg_view_field([
	'#type' => 'text',
	'#help' => elgg_echo('user:username:help'),
	'name' => 'username',
	'value' => $user->username,
]);

echo elgg_view_module('info', elgg_echo('username'), $body);
