<?php
/**
 * Delete message
 */

$guid = (int) get_input('guid');

$message = get_entity($guid);
if (!$message || !$message->canEdit()) {
	register_error(elgg_echo('messages:error:delete:single'));
	forward(REFERER);
}

if (!$message->delete()) {
	register_error(elgg_echo('messages:error:delete:single'));
} else {
	system_message(elgg_echo('messages:success:delete:single'));
}

forward(REFERER);
