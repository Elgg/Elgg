<?php

	/**
	 * Elgg upload new profile icon
	 * 
	 * @package ElggProfile
	 */

	// Load the Elgg framework
	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
	// Make sure we're logged in
	if (!isloggedin()) {
		forward();
	}

	// Get owner of profile - set in page handler
	$user = page_owner_entity();
	if (!$user) {
		register_error(elgg_echo("profile:notfound"));
		forward();
	}

	// check if logged in user can edit this profile icon
	if (!$user->canEdit()) {
		register_error(elgg_echo("profile:icon:noaccess"));
		forward();
	}
	
	// set title
	$area2 = elgg_view_title(elgg_echo('profile:createicon:header'));
	$area2 .= elgg_view("profile/editicon", array('user' => $user));
	
	// Get the form and correct canvas area
	$body = elgg_view_layout("two_column_left_sidebar", '', $area2);
	
	// Draw the page
	page_draw(elgg_echo("profile:editicon"), $body);
