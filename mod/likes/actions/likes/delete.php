<?php
/**
 * Elgg delete like action
 *
 */

$id = (int) get_input('id');
$like = elgg_get_annotation_from_id($id);
if ($like && $like->canEdit()) {
	$like->delete();
	system_message(elgg_echo("likes:deleted"));
	forward(REFERER);
}

register_error(elgg_echo("likes:notdeleted"));
forward(REFERER);
