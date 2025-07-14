<?php
/**
 * Bans a user.
 */

$guid = (int) get_input('guid');
$user = get_user($guid);

if ($guid == elgg_get_logged_in_user_guid()) {
	return elgg_error_response(elgg_echo('admin:user:self:ban:no'));
}

if (!$user || !$user->canEdit()) {
	return elgg_error_response(elgg_echo('admin:user:ban:no'));
}

if (!$user->ban('banned')) {
	return elgg_error_response(elgg_echo('admin:user:ban:no'));
}

if (elgg_get_config('security_notify_user_ban')) {
	// this can't be handled by the delayed notification system as it won't send notifications to banned users
	$user->notify('ban', $user);
}

return elgg_ok_response('', elgg_echo('admin:user:ban:yes'));
