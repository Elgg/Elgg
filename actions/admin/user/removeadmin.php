<?php
/**
 * Revokes admin privileges from a user.
 */

$guid = (int) get_input('guid');
$user = get_user($guid);

if ($guid == elgg_get_logged_in_user_guid()) {
	return elgg_error_response(elgg_echo('admin:user:self:removeadmin:no'));
}

if (!$user || !$user->canEdit()) {
	return elgg_error_response(elgg_echo('admin:user:removeadmin:no'));
}

if (!$user->removeAdmin()) {
	return elgg_error_response(elgg_echo('admin:user:removeadmin:no'));
}

return elgg_ok_response('', elgg_echo('admin:user:removeadmin:yes'));
