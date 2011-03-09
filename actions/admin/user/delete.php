<?php
/**
 * Delete a user.
 *
 * The user will be deleted recursively, meaning all entities
 * owned or contained by the user will also be removed.
 *
 * @package Elgg.Core
 * @subpackage Administration.User
 */

// Get the user
$guid = get_input('guid');
$user = get_entity($guid);

if ($guid == elgg_get_logged_in_user_guid()) {
	register_error(elgg_echo('admin:user:self:delete:no'));
	forward(REFERER);
}

$name = $user->name;
$username = $user->username;

if (($user instanceof ElggUser) && ($user->canEdit())) {
	if ($user->delete()) {
		system_message(elgg_echo('admin:user:delete:yes', array($name)));
	} else {
		register_error(elgg_echo('admin:user:delete:no'));
	}
} else {
	register_error(elgg_echo('admin:user:delete:no'));
}

// forward to user administration if on a user's page as it no longer exists
$forward = REFERER;
if (strpos($_SERVER['HTTP_REFERER'], $username) != FALSE) {
	$forward = "admin/users/newest";
}

forward($forward);
