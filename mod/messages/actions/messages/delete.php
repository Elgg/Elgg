<?php
/**
 * Delete message
 */

$guid = (int) get_input('guid');
$full = (bool) get_input('full', false);

$message = get_entity($guid);
$forward = REFERER;

if (!elgg_instanceof($message, 'object', 'messages') || !$message->canEdit()) {
	register_error(elgg_echo('messages:error:delete:single'));
	forward($forward);
}

if (!$message->delete()) {
	register_error(elgg_echo('messages:error:delete:single'));
} else {
	if ($full) {
		$forward = 'messages/inbox/' . elgg_get_logged_in_user_entity()->username;
	}
	system_message(elgg_echo('messages:success:delete:single'));
}

forward($forward);
