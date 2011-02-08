<?php
/**
 * Action for changing a user's password
 *
 * @package Elgg
 * @subpackage Core
 */

$current_password = get_input('current_password');
$password = get_input('password');
$password2 = get_input('password2');
$user_id = get_input('guid');

if (!$user_id) {
	$user = elgg_get_logged_in_user_entity();
} else {
	$user = get_entity($user_id);
}

if (($user) && ($password != "")) {
	// let admin user change anyone's password without knowing it except his own.
	if (!elgg_is_admin_logged_in() || elgg_is_admin_logged_in() && $user->guid == elgg_get_logged_in_user_guid()) {
		$credentials = array(
			'username' => $user->username,
			'password' => $current_password
		);

		if (!pam_auth_userpass($credentials)) {
			register_error(elgg_echo('user:password:fail:incorrect_current_password'));
			forward(REFERER);
		}
	}

	if (strlen($password) >= 4) {
		if ($password == $password2) {
			$user->salt = generate_random_cleartext_password(); // Reset the salt
			$user->password = generate_user_password($user, $password);
			if ($user->save()) {
				system_message(elgg_echo('user:password:success'));
			} else {
				register_error(elgg_echo('user:password:fail'));
			}
		} else {
			register_error(elgg_echo('user:password:fail:notsame'));
		}
	} else {
		register_error(elgg_echo('user:password:fail:tooshort'));
	}
}
