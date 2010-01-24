<?php
/**
 * Action to reset a password and send success email.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

global $CONFIG;

$user_guid = get_input('u');
$code = get_input('c');

if (execute_new_password_request($user_guid, $code)) {
	system_message(elgg_echo('user:password:success'));
} else {
	register_error(elgg_echo('user:password:fail'));
}

forward();
exit;
