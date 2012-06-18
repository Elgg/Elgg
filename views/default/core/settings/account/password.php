<?php

/**
 * Provide a way of setting your password
 *
 * @package Elgg
 * @subpackage Core
 */
$user = elgg_get_page_owner_entity();

if ($user) {
	$title = elgg_echo('user:set:password');

	// only make the admin user enter current password for changing his own password.
	$admin = '';
	if (!elgg_is_admin_logged_in() || elgg_is_admin_logged_in() && $user->guid == elgg_get_logged_in_user_guid()) {
		$admin .= elgg_echo('user:current_password:label') . ': ';
		$admin .= elgg_view('input/password', array('name' => 'current_password'));
		$admin = "<p>$admin</p>";
	}

	$password = elgg_echo('user:password:label') . ': ';
	$password .= elgg_view('input/password', array('name' => 'password'));
	$password = "<p>$password</p>";

	$password2 = elgg_echo('user:password2:label') . ': ';
	$password2 .= elgg_view('input/password', array('name' => 'password2'));
	$password2 = "<p>$password2</p>";

	$content = $admin . $password . $password2;

	echo elgg_view_module('info', $title, $content);
}
