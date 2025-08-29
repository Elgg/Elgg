<?php
/**
 * Elgg external pages: create or update
 */

// Get input data and don't filter the content
$contents = get_input('expagescontent', '', false);
$subtype = get_input('content_type');
$guid = (int) get_input('guid');

if (empty($contents) || empty($subtype)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if ($guid) {
	// update
	$expages = get_entity($guid);
	if (!$expages) {
		return elgg_error_response(elgg_echo('expages:error'));
	}
} else {
	// create
	$expages = new \ElggExternalPage();
	$expages->setSubtype($subtype);
}

$expages->owner_guid = elgg_get_logged_in_user_guid();
$expages->title = $subtype;
$expages->description = $contents;

if (!$expages->save()) {
	return elgg_error_response(elgg_echo('expages:error'));
}

if (get_input('header_remove')) {
	$expages->deleteIcon('header');
} else {
	$expages->saveIconFromUploadedFile('header', 'header');
}

return elgg_ok_response('', elgg_echo('expages:posted'));
