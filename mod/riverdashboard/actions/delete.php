<?php

/**
 * Elgg site message: delete
 *
 * @package ElggBlog
 */

// Get input data
$guid = (int) get_input('message');

// Make sure we actually have permission to edit
$message = get_entity($guid);
if ($message->getSubtype() == "sitemessage" && $message->canEdit()) {

	// Delete it!
	$rowsaffected = $message->delete();
	if ($rowsaffected > 0) {
		// Success message
		system_message(elgg_echo("sitemessage:deleted"));
	} else {
		register_error(elgg_echo("sitemessage:notdeleted"));
	}
	// Forward to the river
	forward("mod/riverdashboard/");

}

