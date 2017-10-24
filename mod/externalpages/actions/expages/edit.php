<?php
/**
 * Elgg external pages: create or update
 */

// Get input data and don't filter the content
$contents = get_input('expagescontent', '', false);
$type = get_input('content_type');
$guid = (int) get_input('guid');

if ($guid) {
	// update
	$expages = get_entity($guid);
	if (!$expages) {
		return elgg_error_response(elgg_echo('expages:error'));
	}
} else {
	// create
	$expages = new \ElggObject();
	$expages->subtype = $type;
}

$expages->owner_guid = elgg_get_logged_in_user_guid();
$expages->access_id = ACCESS_PUBLIC;
$expages->title = $type;
$expages->description = $contents;

if (!$expages->save()) {
	return elgg_error_response(elgg_echo('expages:error'));
}

return elgg_ok_response('', elgg_echo('expages:posted'));
