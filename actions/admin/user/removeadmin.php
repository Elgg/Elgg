<?php
/**
 * Revokes admin privileges from a user.
 *
 * @package Elgg.Core
 * @subpackage Administration.User
 */

admin_gatekeeper();

$guid = get_input('guid');
$user = get_entity($guid);

if (($user instanceof ElggUser) && ($user->canEdit())) {
	if ($user->removeAdmin()) {
		system_message(elgg_echo('admin:user:removeadmin:yes'));
	} else {
		register_error(elgg_echo('admin:user:removeadmin:no'));
	}
} else {
	register_error(elgg_echo('admin:user:removeadmin:no'));
}

forward($_SERVER['HTTP_REFERER']);
