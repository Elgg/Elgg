<?php
/**
 * Action to reset a password, send success email, and log the user in.
 */

use Elgg\Exceptions\Configuration\RegistrationException;
use Elgg\Exceptions\Http\LoginException;

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
if (!$user instanceof \ElggUser) {
	return elgg_error_response(elgg_echo('user:changepassword:unknown_user'));
}

$saved_code = $user->passwd_conf_code;
$codes_match = _elgg_services()->crypto->areEqual($saved_code, $code);

if (!$saved_code || !$codes_match) {
	return elgg_error_response(elgg_echo('user:changepassword:unknown_user'));
}

// Discard for security if it is 24h old
$code_time = (int) $user->passwd_conf_time;
if (!$code_time || $code_time < time() - 24 * 60 * 60) {
	return elgg_error_response(elgg_echo('user:changepassword:unknown_user'));
}

elgg()->accounts->changePassword($user, $password);

unset($user->passwd_conf_code);
unset($user->passwd_conf_time);

$user->notify('changepassword', $user, [
	'password' => $password,
]);

try {
	elgg_login($user);
} catch (LoginException $e) {
	return elgg_error_response($e->getMessage());
}

return elgg_ok_response('', elgg_echo('user:password:success'), '');
