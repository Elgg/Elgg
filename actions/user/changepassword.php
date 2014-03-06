<?php
/**
 * Action to reset a password, send success email, and log the user in.
 *
 * @package Elgg
 * @subpackage Core
 */

$password = get_input('password1');
$password_repeat = get_input('password2');
$user_guid = get_input('u');
$code = get_input('c');

try {
	validate_password($password);
} catch(RegistrationException $e) {
	register_error($e->getMessage());
	forward(REFERER);
}

if ($password != $password_repeat) {
	register_error(elgg_echo('RegistrationException:PasswordMismatch'));
	forward(REFERER);
}

if (execute_new_password_request($user_guid, $code, $password)) {
	system_message(elgg_echo('user:password:success'));
	login(get_entity($user_guid));
} else {
	register_error(elgg_echo('user:password:fail'));
}

forward();

