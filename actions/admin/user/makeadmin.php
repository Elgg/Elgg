<?php
/**
 * Grants admin privileges to a user.
 *
 * @package Elgg.Core
 * @subpackage Administration.User
 */

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

forward(REFERER);
