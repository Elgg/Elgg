<?php
/**
 * Elgg delete user
 *
 * @package Elgg
 * @subpackage Core
 */


// block non-admin users - require since this action is not registered
admin_gatekeeper();

// Get the user
$guid = get_input('guid');
$obj = get_entity($guid);

$name = $obj->name;
$username = $obj->username;

if (($obj instanceof ElggUser) && ($obj->canEdit())) {
	if ($obj->delete()) {
		system_message(sprintf(elgg_echo('admin:user:delete:yes'), $name));
	} else {
		register_error(elgg_echo('admin:user:delete:no'));
	}
} else {
	register_error(elgg_echo('admin:user:delete:no'));
}

// forward to user administration if on a user's page as it no longer exists
$forward = REFERER;
if (strpos($_SERVER['HTTP_REFERER'], $username) != FALSE) {
	$forward = "pg/admin/user/";
}

forward($forward);
