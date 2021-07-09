<?php
/**
 * Grants admin privileges to a user.
 */

$guid = (array) get_input('guid');
if (empty($guid)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$user = get_user($guid[0]);
if (!$user || !$user->canEdit()) {
	return elgg_error_response(elgg_echo('admin:user:makeadmin:no'));
}

if (!$user->makeAdmin()) {
	return elgg_error_response(elgg_echo('admin:user:makeadmin:no'));
}

return elgg_ok_response('', elgg_echo('admin:user:makeadmin:yes'));
