<?php
/**
 * Delete discussion reply
 */

$guid = (int) get_input('guid');

$reply = get_entity($guid);

if (!elgg_instanceof($reply, 'object', 'discussion_reply')) {
	return elgg_error_response(elgg_echo('discussion:reply:error:notdeleted'));
}

if (!$reply->canDelete()) {
	return elgg_error_response(elgg_echo('discussion:error:permissions'));
}

if (!$reply->delete()) {
	return elgg_error_response(elgg_echo('discussion:reply:error:notdeleted'));
}

return elgg_ok_response('', elgg_echo('discussion:reply:deleted'));
