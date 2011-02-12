<?php
/**
 * Elgg notifications plugin group index
 *
 * @package ElggNotifications
 */

// Load Elgg framework
require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');

// Ensure only logged-in users can see this page
gatekeeper();

set_page_owner(elgg_get_logged_in_user_guid());

// Set the context to settings
elgg_set_context('settings');

$title = elgg_echo('notifications:subscriptions:changesettings:groups');

// Get the form
$people = array();

$groupmemberships = elgg_get_entities_from_relationship(array('relationship' => 'member', 'relationship_guid' => elgg_get_logged_in_user_guid(), 'types' => 'group', 'limit' => 9999));

$body = elgg_view_form('notificationsettings/groupsave', array(), array('groups' => $groupmemberships));

$params = array(
	'content' => $body,
	'title' => $title,
);
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
