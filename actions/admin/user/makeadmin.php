<?php
/**
 * Grants admin privileges to a user.
 */

$guid = (int) get_input('guid');
$user = get_user($guid);

if (!$user || !$user->canEdit()) {
	return elgg_error_response(elgg_echo('admin:user:makeadmin:no'));
}

if (!$user->makeAdmin()) {
	return elgg_error_response(elgg_echo('admin:user:makeadmin:no'));
}

return elgg_ok_response('', elgg_echo('admin:user:makeadmin:yes'));
