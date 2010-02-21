<?php
/**
 * Admin password reset.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

global $CONFIG;

// block non-admin users
admin_gatekeeper();

// Get the user
$guid = get_input('guid');
$obj = get_entity($guid);

if (($obj instanceof ElggUser) && ($obj->canEdit())) {
	$password = generate_random_cleartext_password();

	$obj->salt = generate_random_cleartext_password(); // Reset the salt
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

forward($_SERVER['HTTP_REFERER']);
exit;
