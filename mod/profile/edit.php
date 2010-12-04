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
$user = elgg_get_page_owner();
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
$area1 = elgg_view_title(elgg_echo('profile:edit'));
$area1 .= elgg_view("profile/edit",array('entity' => $user)); 
	
elgg_set_context('profile_edit');

$body = elgg_view_layout("one_column_with_sidebar", array('content' => $area1));
	
echo elgg_view_page(elgg_echo("profile:edit"), $body);
