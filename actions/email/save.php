<?php
/**
 * Save email address for user.
 *
 * @package Elgg.Core
 * @subpackage Administration.Users
 */

$email = get_input('email');
$user_id = get_input('guid');

if (!$user_id) {
	$user = elgg_get_logged_in_user_entity();
} else {
	$user = get_entity($user_id);
}

if (!is_email_address($email)) {
	register_error(elgg_echo('email:save:fail'));
	forward(REFERER);
}

if ($user) {
	if (strcmp($email, $user->email) != 0) {
		if (!get_user_by_email($email)) {
			if ($user->email != $email) {

				$user->email = $email;
				if ($user->save()) {
					system_message(elgg_echo('email:save:success'));
				} else {
					register_error(elgg_echo('email:save:fail'));
				}
			}
		} else {
			register_error(elgg_echo('registration:dupeemail'));
		}
	}
} else {
	register_error(elgg_echo('email:save:fail'));
}
