<?php
/**
 * Revokes admin privileges from a user.
 *
 * @package Elgg.Core
 * @subpackage Administration.User
 */

$guid = get_input('guid');
$user = get_entity($guid);

if ($guid == elgg_get_logged_in_user_guid()) {
	register_error(elgg_echo('admin:user:self:removeadmin:no'));
	forward(REFERER);
}

if (($user instanceof ElggUser) && ($user->canEdit())) {
	if ($user->removeAdmin()) {
		system_message(elgg_echo('admin:user:removeadmin:yes'));
	} else {
		register_error(elgg_echo('admin:user:removeadmin:no'));
	}
} else {
	register_error(elgg_echo('admin:user:removeadmin:no'));
}

forward(REFERER);
