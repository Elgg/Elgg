<?php
/**
 * Elgg ban user
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */


// block non-admin users
admin_gatekeeper();

// Get the user
$guid = get_input('guid');
$obj = get_entity($guid);

if (($obj instanceof ElggUser) && ($obj->canEdit())) {
	// Now actually disable it
	if ($obj->ban('banned')) {
		system_message(elgg_echo('admin:user:ban:yes'));
	} else {
		register_error(elgg_echo('admin:user:ban:no'));
	}
} else {
	register_error(elgg_echo('admin:user:ban:no'));
}

forward('pg/admin/user/');
exit;
