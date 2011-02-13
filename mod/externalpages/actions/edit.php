<?php
/**
 * Elgg external pages: add/edit
 *
 */

// Get input data
$contents = get_input('expagescontent', '', false);
$type = get_input('content_type');
$previous_guid = get_input('expage_guid');

// create object to hold the page details
$expages = new ElggObject();
$expages->subtype = $type;
$expages->owner_guid = get_loggedin_userid();
$expages->access_id = ACCESS_PUBLIC;
$expages->title = $type;
$expages->description = $contents;
if (!$expages->save()) {
	register_error(elgg_echo("expages:error"));
	forward(REFERER);
}

system_message(elgg_echo("expages:posted"));
forward(REFERER);
