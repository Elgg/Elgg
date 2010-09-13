<?php
/**
 * Bans a user.
 *
 * User entities are banned by setting the 'banned' column
 * to 'yes' in the users_entity table.
 *
 * @package Elgg.Core
 * @subpackage Administration.User
 */

admin_gatekeeper();

$guid = get_input('guid');
$obj = get_entity($guid);

if (($obj instanceof ElggUser) && ($obj->canEdit())) {
	if ($obj->ban('banned')) {
		system_message(elgg_echo('admin:user:ban:yes'));
	} else {
		register_error(elgg_echo('admin:user:ban:no'));
	}
} else {
	register_error(elgg_echo('admin:user:ban:no'));
}

forward('pg/admin/user/');