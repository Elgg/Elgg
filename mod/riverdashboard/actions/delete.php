<?php

/**
 * Elgg site message: delete
 *
 * @package ElggRiverDash
 */

// Get input data
$guid = (int) get_input('message_guid');

// Make sure we actually have permission to edit
$message = get_entity($guid);
if ($message->getSubtype() != "sitemessage" || !$message->canEdit()) {
	register_error(elgg_echo("sitemessage:notdeleted"));
	forward(REFERER);
}

// Delete it!
$rowsaffected = $message->delete();
if ($rowsaffected > 0) {
	system_message(elgg_echo("sitemessage:deleted"));
} else {
	register_error(elgg_echo("sitemessage:notdeleted"));
}

// Forward to the river
forward(REFERER);
