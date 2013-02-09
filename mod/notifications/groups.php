<?php
/**
 * Elgg notifications plugin group index
 *
 * @package ElggNotifications
 *
 * @uses $user ElggUser
 */

if (!isset($user) || !($user instanceof ElggUser)) {
	$url = 'notifications/group/' . elgg_get_logged_in_user_entity()->username;
	forward($url);
}

elgg_set_page_owner_guid($user->guid);

// Set the context to settings
elgg_set_context('settings');

$title = elgg_echo('notifications:subscriptions:changesettings:groups');

elgg_push_breadcrumb(elgg_echo('settings'), "settings/user/$user->username");
elgg_push_breadcrumb($title);

// Get the form
$people = array();

$groupmemberships = elgg_get_entities_from_relationship(array(
	'relationship' => 'member',
	'relationship_guid' => $user->guid,
	'type' => 'group',
	'limit' => false,
));

$body = elgg_view_form('notificationsettings/groupsave', array(), array(
	'groups' => $groupmemberships,
	'user' => $user,
));

$params = array(
	'content' => $body,
	'title' => $title,
);
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
