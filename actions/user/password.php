<?php
/**
 * Action for changing a user's password
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

global $CONFIG;

gatekeeper();

$password = get_input('password');
$password2 = get_input('password2');
$user_id = get_input('guid');
$user = "";

if (!$user_id) {
	$user = $_SESSION['user'];
} else {
	$user = get_entity($user_id);
}

if (($user) && ($password!="")) {
	if (strlen($password)>=4) {
		if ($password == $password2) {
			$user->salt = generate_random_cleartext_password(); // Reset the salt
			$user->password = generate_user_password($user, $password);
			if ($user->save()) {
				system_message(elgg_echo('user:password:success'));
			} else {
				register_error(elgg_echo('user:password:fail'));
			}
		} else {
			register_error(elgg_echo('user:password:fail:notsame'));
		}
	} else {
		register_error(elgg_echo('user:password:fail:tooshort'));
	}
}
