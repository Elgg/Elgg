<?php
/**
 * Make another user an admin.
 *
 * @package Elgg
 * @subpackage Core
 */

global $CONFIG;

// block non-admin users
admin_gatekeeper();

// Get the user
$guid = get_input('guid');
$user = get_entity($guid);

if (($user instanceof ElggUser) && ($user->canEdit())) {
	if ($user->makeAdmin()) {
		system_message(elgg_echo('admin:user:makeadmin:yes'));
	} else {
		register_error(elgg_echo('admin:user:makeadmin:no'));
	}
} else {
	register_error(elgg_echo('admin:user:makeadmin:no'));
}

forward($_SERVER['HTTP_REFERER']);
