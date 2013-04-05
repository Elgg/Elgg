<?php
/**
 * Delete comment entity
 *
 * @package Elgg.Core
 * @subpackage Comments
 */

$comment_guid = get_input('guid');
$comment = get_entity($comment_guid);

if (elgg_instanceof($comment, 'object', 'comment') && $comment->canEdit()) {
	if ($comment->delete()) {
		system_message(elgg_echo('generic_comment:deleted'));
	} else {
		register_error(elgg_echo('generic_comment:notdeleted'));
	}
} else {
	register_error(elgg_echo('generic_comment:notfound'));
}

forward(REFERER);