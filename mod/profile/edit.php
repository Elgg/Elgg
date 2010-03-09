<?php

/**
 * Elgg profile editor
 * 
 * @package ElggProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
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
$area1 = elgg_view_title(elgg_echo('profile:edit'));
$area1 .= elgg_view("profile/edit",array('entity' => $user)); 
	
set_context('profile_edit');

// get the required canvas area
$body = elgg_view_layout("one_column_with_sidebar", $area1);
	
// Draw the page
page_draw(elgg_echo("profile:edit"),$body);
