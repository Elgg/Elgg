<?php
/**
 * Elgg external pages: create or update
 *
 */

// Get input data and don't filter the content
$contents = get_input('expagescontent', '', false);
$type = get_input('content_type');
$guid = get_input('guid');

if ($guid) {
	// update
	$expages = get_entity($guid);
	if (!$expages) {
		register_error(elgg_echo("expages:error"));
		forward(REFERER);
	}
} else {
	// create
	$expages = new ElggObject();
	$expages->subtype = $type;
}

$expages->owner_guid = elgg_get_logged_in_user_guid();
$expages->access_id = ACCESS_PUBLIC;
$expages->title = $type;
$expages->description = $contents;
if (!$expages->save()) {
	register_error(elgg_echo("expages:error"));
	forward(REFERER);
}

system_message(elgg_echo("expages:posted"));
forward(REFERER);
