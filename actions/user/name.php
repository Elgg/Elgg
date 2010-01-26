<?php
/**
 * Action for changing a user's name
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

global $CONFIG;

gatekeeper();

$name = strip_tags(get_input('name'));
$user_id = get_input('guid');
$user = "";

if (!$user_id) {
	$user = $_SESSION['user'];
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
