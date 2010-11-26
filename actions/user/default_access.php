<?php
/**
 * Action for changing a user's default access level
 *
 * @package Elgg
 * @subpackage Core
 */

global $CONFIG;

if ($CONFIG->allow_user_default_access) {
	gatekeeper();

	$default_access = get_input('default_access');
	$user_id = get_input('guid');

	if (!$user_id) {
		$user = get_loggedin_user();
	} else {
		$user = get_entity($user_id);
	}

	if ($user) {
		$current_default_access = $user->getPrivateSetting('elgg_default_access');
		if ($default_access !== $current_default_access) {
			if ($user->setPrivateSetting('elgg_default_access', $default_access)) {
				system_message(elgg_echo('user:default_access:success'));
			} else {
				register_error(elgg_echo('user:default_access:fail'));
			}
		}
	} else {
		register_error(elgg_echo('user:default_access:fail'));
	}
}
