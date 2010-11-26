<?php

	/**
	 * Elgg profile editor
	 * 
	 * @package ElggProfile
	 */

	// Get the Elgg engine
	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	// If we're not logged on, forward the user elsewhere
	if (!isloggedin()) {
		forward();
	}
	
	// Get owner of profile - set in page handler
	$user = page_owner_entity();
	if (!$user) {
		register_error(elgg_echo("profile:notfound"));
		forward();
	}

	// check if logged in user can edit this profile
	if (!$user->canEdit()) {
		register_error(elgg_echo("profile:noaccess"));
		forward();
	}
	
	// Get edit form
	$area2 = elgg_view_title(elgg_echo('profile:edit'));
	$area2 .= elgg_view("profile/edit",array('entity' => $user)); 
	
	$area1 = "";
	
	// get the required canvas area
	$body = elgg_view_layout("two_column_left_sidebar", $area1, $area2);
	
	// Draw the page
	page_draw(elgg_echo("profile:edit"),$body);
