<?php
/**
 * Elgg delete comment action
 *
 * @package Elgg
 */

// Make sure we can get the comment in question
$annotation_id = (int) get_input('annotation_id');
$comment = elgg_get_annotation_from_id($annotation_id);
if ($comment && $comment->canEdit()) {
	$comment->delete();
	system_message(elgg_echo("generic_comment:deleted"));
} else {
	register_error(elgg_echo("generic_comment:notdeleted"));
}

forward(REFERER);