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

$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

// Get the user
$guid = get_input('guid');
$obj = get_entity($guid);

if (($obj instanceof ElggUser) && ($obj->canEdit())) {
	// Now actually disable it
	if ($obj->unban()) {
		system_message(elgg_echo('admin:user:unban:yes'));
	} else {
		register_error(elgg_echo('admin:user:unban:no'));
	}
} else {
	register_error(elgg_echo('admin:user:unban:no'));
}

access_show_hidden_entities($access_status);

forward($_SERVER['HTTP_REFERER']);
exit;
