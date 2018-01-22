<?php
/**
 * Action to reset a password, send success email, and log the user in.
 */

$password = get_input('password1');
$password_repeat = get_input('password2');
$user_guid = (int) get_input('u');
$code = get_input('c');

try {
	validate_password($password);
} catch (RegistrationException $e) {
	return elgg_error_response($e->getMessage());
}

if ($password != $password_repeat) {
	return elgg_error_response(elgg_echo('RegistrationException:PasswordMismatch'));
}

if (!execute_new_password_request($user_guid, $code, $password)) {
	return elgg_error_response(elgg_echo('user:password:fail'));
}

try {
	login(get_user($user_guid));
} catch (LoginException $e) {
	return elgg_error_response($e->getMessage());
}

return elgg_ok_response('', elgg_echo('user:password:success'), '');
