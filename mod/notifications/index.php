<?php

/**
 * Elgg notifications plugin index
 *
 * @package ElggNotifications
 */

// Load Elgg framework
require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');

// Ensure only logged-in users can see this page
gatekeeper();

set_page_owner(get_loggedin_userid());

// Set the context to settings
set_context('settings');

// Get the form
$people = array();
if ($people_ents = elgg_get_entities_from_relationship(array('relationship' => 'notify', 'relationship_guid' => get_loggedin_userid(), 'types' => 'user', 'limit' => 99999))) {
	foreach($people_ents as $ent) {
		$people[] = $ent->guid;
	}
}

$body = elgg_view('notifications/subscriptions/form', array('people' => $people));

// Insert it into the correct canvas layout
$body = elgg_view_layout('two_column_left_sidebar', '', $body);


page_draw(elgg_echo('notifications:subscriptions:changesettings'), $body);
