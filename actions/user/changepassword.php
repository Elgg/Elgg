<?php
/**
 * Action to reset a password, send success email, and log the user in.
 */

use Elgg\Exceptions\Configuration\RegistrationException;
use Elgg\Exceptions\LoginException;

$password = (string) get_input('password1', '', false);
$password_repeat = (string) get_input('password2', '', false);
$user_guid = (int) get_input('u');
$code = (string) get_input('c');

try {
	elgg()->accounts->assertValidPassword($password);
} catch (RegistrationException $e) {
	return elgg_error_response($e->getMessage());
}

if ($password !== $password_repeat) {
	return elgg_error_response(elgg_echo('RegistrationException:PasswordMismatch'));
}

$user = get_user($user_guid);
if (!$user instanceof \ElggUser || !elgg_save_new_password($user, $code, $password)) {
	return elgg_error_response(elgg_echo('user:changepassword:unknown_user'));
}

try {
	elgg_login($user);
} catch (LoginException $e) {
	return elgg_error_response($e->getMessage());
}

return elgg_ok_response('', elgg_echo('user:password:success'), '');
