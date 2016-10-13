<?php
/**
 * Set a user's password
 */

$current_password = get_input('current_password', null, false);
$password = get_input('password', null, false);
$password2 = get_input('password2', null, false);
$user_guid = (int) get_input('guid');

if ($user_guid) {
	$user = get_user($user_guid);
} else {
	$user = elgg_get_logged_in_user_entity();
}

if (!$user || !$password || !$password2) {
	return elgg_error_response(elgg_echo('user:password:fail'));
}

// let admin user change anyone's password without knowing it except his own.
if (!elgg_is_admin_logged_in() || elgg_is_admin_logged_in() && $user->guid == elgg_get_logged_in_user_guid()) {
	try {
		pam_auth_userpass([
			'username' => $user->username,
			'password' => $current_password,
		]);
	} catch (LoginException $e) {
		return elgg_error_response(elgg_echo('LoginException:ChangePasswordFailure'));
	}
}

try {
	$result = validate_password($password);
} catch (RegistrationException $e) {
	return elgg_error_response($e->getMessage());
}

if (!$result) {
	return elgg_error_response(elgg_echo('user:password:fail:tooshort'));
}

if ($password !== $password2) {
	return elgg_error_response(elgg_echo('user:password:fail:notsame'));
}

$user->setPassword($password);
_elgg_services()->persistentLogin->handlePasswordChange($user, elgg_get_logged_in_user_entity());

if (!$user->save()) {
	return elgg_error_response(elgg_echo('user:password:fail'));
}

return elgg_ok_response('', elgg_echo('user:password:success'));
