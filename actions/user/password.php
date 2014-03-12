<?php
/**
 * Action for changing a user's password
 *
 * @package Elgg
 * @subpackage Core
 */

gatekeeper();

$current_password = get_input('current_password');
$password = get_input('password');
$password2 = get_input('password2');
$user_id = get_input('guid');

if (!$user_id) {
	$user = get_loggedin_user();
} else {
	$user = get_entity($user_id);
}

if (($user) && ($password != "")) {
	// let admin user change anyone's password without knowing it except his own.
	if (!isadminloggedin() || isadminloggedin() && $user->guid == get_loggedin_userid()) {
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
			$user->salt = _elgg_generate_password_salt();
			$user->password = generate_user_password($user, $password);
			$user->code = '';
			if ($user->guid == get_loggedin_userid() && !empty($_COOKIE['elggperm'])) {
				// regenerate remember me code so no other user could
				// use it to authenticate later
				$code = _elgg_generate_remember_me_token();
				$_SESSION['code'] = $code;
				$user->code = md5($code);
				setcookie("elggperm", $code, (time() + (86400 * 30)), "/");
			}
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
