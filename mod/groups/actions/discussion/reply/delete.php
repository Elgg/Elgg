<?php
/**
 * Delete discussion reply
 */

$guid = (int) get_input('guid');

$reply = get_entity($guid);

if (!elgg_instanceof($reply, 'object', 'discussion_reply', 'ElggDiscussionReply')) {
	register_error(elgg_echo('discussion:reply:error:notdeleted'));
	forward(REFERER);
}

if (!$reply->canEdit()) {
	register_error(elgg_echo('discussion:error:permissions'));
	forward(REFERER);
}

if ($reply->delete()) {
	system_message(elgg_echo('discussion:reply:deleted'));
} else {
	register_error(elgg_echo('discussion:reply:error:notdeleted'));
}

forward(REFERER);
