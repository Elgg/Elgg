<?php
/**
 * Unbans a user.
 */

$access_status = access_show_hidden_entities(true);

$guid = (int) get_input('guid');
$user = get_user($guid);

if (!$user || !$user->canEdit()) {
	access_show_hidden_entities($access_status);
	return elgg_error_response(elgg_echo('admin:user:unban:no'));
}

if (!$user->unban()) {
	access_show_hidden_entities($access_status);
	return elgg_error_response(elgg_echo('admin:user:unban:no'));
}

access_show_hidden_entities($access_status);

return elgg_ok_response('', elgg_echo('admin:user:unban:yes'));
