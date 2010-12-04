<?php

/**
 * Elgg site message: add
 *
 * @package ElggSiteMessage
 **/

$message = get_input('sitemessage');
//$access = 1; //it is for all logged in users

// Make sure the message isn't blank
if (empty($message)) {
	register_error(elgg_echo("sitemessages:blank"));
	forward("mod/riverdashboard/");

	// Otherwise, save the message
} else {

	// Initialise a new ElggObject
	$sitemessage = new ElggObject();
	// Tell the system it's a site wide message
	$sitemessage->subtype = "sitemessage";
	// Set its owner to the current user
	$sitemessage->owner_guid = get_loggedin_userid();
	// For now, set its access to logged in users
	$sitemessage->access_id = 1; // this is for all logged in users
	// Set description appropriately
	$sitemessage->title = '';
	$sitemessage->description = $message;
	// Before we can set metadata, we need to save the message
	if (!$sitemessage->save()) {
		register_error(elgg_echo("sitemessage:error"));
		forward("mod/riverdashboard/");
	}
	// Success message
	system_message(elgg_echo("sitemessages:posted"));

	// add to river
	add_to_river('river/sitemessage/create','create',get_loggedin_userid(),$sitemessage->guid);

	// Forward to the activity page
	forward("mod/riverdashboard/");

}
