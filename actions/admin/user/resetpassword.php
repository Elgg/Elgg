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
 */

$guid = (int) get_input('guid');
$user = get_user($guid);

if (!$user || !$user->canEdit()) {
	return elgg_error_response(elgg_echo('admin:user:resetpassword:no'));
}

$password = generate_random_cleartext_password();

if (!force_user_password_reset($user->guid, $password)) {
	return elgg_error_response(elgg_echo('admin:user:resetpassword:no'));
}

notify_user($user->guid,
	elgg_get_site_entity()->guid,
	elgg_echo('email:resetpassword:subject', [], $user->language),
	elgg_echo('email:resetpassword:body', [$user->username, $password], $user->language),
	[
		'object' => $user,
		'action' => 'resetpassword',
		'password' => $password,
	],
	'email');

return elgg_ok_response('', elgg_echo('admin:user:resetpassword:yes'));
