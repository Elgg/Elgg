<?php
/**
 * Elgg Message board: delete message action
 *
 * @package ElggMessageBoard
 */

$annotation_id = (int) get_input('annotation_id');
$message = elgg_get_annotation_from_id($annotation_id);

if ($message && $message->canEdit() && $message->delete()) {
	system_message(elgg_echo("messageboard:deleted"));
} else {
	system_message(elgg_echo("messageboard:notdeleted"));
}

forward(REFERER);
