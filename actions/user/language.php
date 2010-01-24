<?php
/**
 * Action for changing a user's personal language settings
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

global $CONFIG;

gatekeeper();

$language = get_input('language');
$user_id = get_input('guid');
$user = "";

if (!$user_id) {
	$user = $_SESSION['user'];
} else {
	$user = get_entity($user_id);
}

if (($user) && ($language)) {
	if (strcmp($language, $user->language)!=0) {
		$user->language = $language;
		if ($user->save()) {
			system_message(elgg_echo('user:language:success'));
		} else {
			register_error(elgg_echo('user:language:fail'));
		}
	}
} else {
	register_error(elgg_echo('user:language:fail'));
}
