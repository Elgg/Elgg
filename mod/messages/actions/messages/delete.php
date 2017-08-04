<?php
/**
 * Delete message
 */

$guid = (int) get_input('guid');
$full = (bool) get_input('full', false);

elgg_entity_gatekeeper($guid, 'object', 'messages');

$message = get_entity($guid);
if (!$message->canEdit() || !$message->delete()) {
	return elgg_error_response(elgg_echo('messages:error:delete:single'));
}

$forward = $full ? 'messages/inbox/' . elgg_get_logged_in_user_entity()->username : REFERER;

return elgg_ok_response('', elgg_echo('messages:success:delete:single'), $forward);
