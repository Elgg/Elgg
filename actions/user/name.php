<?php
/**
 * Action for changing a user's name
 *
 * @package Elgg
 * @subpackage Core
 */

gatekeeper();

$name = strip_tags(get_input('name'));
$user_id = get_input('guid');

if (!$user_id) {
	$user = get_loggedin_user();
} else {
	$user = get_entity($user_id);
}

if (elgg_strlen($name) > 50) {
	register_error(elgg_echo('user:name:fail'));
	forward($_SERVER['HTTP_REFERER']);
}

if (($user) && ($user->canEdit()) && ($name)) {
	if ($name != $user->name) {
		$user->name = $name;
		if ($user->save()) {
			system_message(elgg_echo('user:name:success'));
		} else {
			register_error(elgg_echo('user:name:fail'));
		}
	}
} else {
	register_error(elgg_echo('user:name:fail'));
}
