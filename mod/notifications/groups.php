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

set_page_owner(get_loggedin_userid());

// Set the context to settings
elgg_set_context('settings');

// Get the form
$people = array();

$groupmemberships = elgg_get_entities_from_relationship(array('relationship' => 'member', 'relationship_guid' => get_loggedin_userid(), 'types' => 'group', 'limit' => 9999));

$form_body = elgg_view('notifications/subscriptions/groupsform',array('groups' => $groupmemberships));
$body = elgg_view('input/form',array(
		'body' => $form_body,
		'method' => 'post',
		'action' => 'action/notificationsettings/groupsave'
));

// Insert it into the correct canvas layout
$body = elgg_view_layout('one_column_with_sidebar', array('content' => $body));


echo elgg_view_page(elgg_echo('notifications:subscriptions:changesettings:groups'), $body);
