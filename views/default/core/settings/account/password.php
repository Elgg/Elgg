<?php

/**
 * Provide a way of setting your password
 *
 * @package Elgg
 * @subpackage Core
 */
$user = elgg_get_page_owner_entity();

if (!$user instanceof ElggUser) {
	return;
}

$title = elgg_echo('user:set:password');

// only make the admin user enter current password for changing his own password.
$admin = '';
if (!elgg_is_admin_logged_in() || elgg_is_admin_logged_in() && $user->guid == elgg_get_logged_in_user_guid()) {
	$admin .= elgg_view_field([
	'#type' => 'password',
		'name' => 'current_password',
		'#label' => elgg_echo('user:current_password:label'),
	]);
}

$password = elgg_view_field([
	'#type' => 'password',
	'name' => 'password',
	'#label' => elgg_echo('user:password:label'),
		]);

$password2 = elgg_view_field([
	'#type' => 'password',
	'name' => 'password2',
	'#label' => elgg_echo('user:password2:label')
		]);

$content = $admin . $password . $password2;

echo elgg_view_module('info', $title, $content);
