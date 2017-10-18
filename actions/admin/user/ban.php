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

return elgg_ok_response('', elgg_echo('admin:user:ban:yes'));
