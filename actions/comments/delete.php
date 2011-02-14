<?php
/**
 * Elgg delete comment action
 *
 * @package Elgg
 */

// Ensure we're logged in
if (!elgg_is_logged_in()) {
	forward();
}

// Make sure we can get the comment in question
$annotation_id = (int) get_input('annotation_id');
if ($comment = elgg_get_annotation_from_id($annotation_id)) {

	$entity = get_entity($comment->entity_guid);

	if ($comment->canEdit()) {
		$comment->delete();
		system_message(elgg_echo("generic_comment:deleted"));
		forward($entity->getURL());
	}

} else {
	$url = "";
}

register_error(elgg_echo("generic_comment:notdeleted"));
forward(REFERER);