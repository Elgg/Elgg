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

admin_gatekeeper();

$guid = get_input('guid');
$obj = get_entity($guid);

if (($obj instanceof ElggUser) && ($obj->canEdit())) {
	$password = generate_random_cleartext_password();

	// Always reset the salt before generating the user password.
	$obj->salt = generate_random_cleartext_password();
	$obj->password = generate_user_password($obj, $password);

	if ($obj->save()) {
		system_message(elgg_echo('admin:user:resetpassword:yes'));

		notify_user($obj->guid,
			$CONFIG->site->guid,
			elgg_echo('email:resetpassword:subject'),
			sprintf(elgg_echo('email:resetpassword:body'), $obj->username, $password),
			NULL,
			'email');
	} else {
		register_error(elgg_echo('admin:user:resetpassword:no'));
	}
} else {
	register_error(elgg_echo('admin:user:resetpassword:no'));
}

forward(REFERER);