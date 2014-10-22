<?php
/**
 * Reset a user's password.
 *
 * This is an admin action that generates a new salt and password
 * for a user, then emails the password to the user's registered
 * email address.
 *
 * NOTE: This is different to the "reset password" link users
 * can use in that it does not first email the user asking if
 * they want to have their password reset.
 *
 * @package Elgg.Core
 * @subpackage Administration.User
 */

$guid = get_input('guid');
$user = get_entity($guid);

if (($user instanceof ElggUser) && ($user->canEdit())) {
	$password = generate_random_cleartext_password();

	if (force_user_password_reset($user->guid, $password)) {
		system_message(elgg_echo('admin:user:resetpassword:yes'));

		notify_user($user->guid,
			elgg_get_site_entity()->guid,
			elgg_echo('email:resetpassword:subject', array(), $user->language),
			elgg_echo('email:resetpassword:body', array($user->username, $password), $user->language),
			array(),
			'email');
	} else {
		register_error(elgg_echo('admin:user:resetpassword:no'));
	}
} else {
	register_error(elgg_echo('admin:user:resetpassword:no'));
}

forward(REFERER);
