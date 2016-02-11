<?php
/**
 * Elgg notifications plugin group index
 *
 * @package ElggNotifications
 *
 * @uses $user ElggUser
 */
$current_user = elgg_get_logged_in_user_entity();

$username = elgg_extract('username', $vars);
$user = get_user_by_username($username);
if (($user->guid != $current_user->guid) && !$current_user->isAdmin()) {
	forward();
}


if (!isset($user) || !($user instanceof ElggUser)) {
	$url = 'notifications/group/' . elgg_get_logged_in_user_entity()->username;
	forward($url);
}

elgg_set_page_owner_guid($user->guid);

$title = elgg_echo('notifications:subscriptions:changesettings:groups');

elgg_push_breadcrumb(elgg_echo('settings'), "settings/user/$user->username");
elgg_push_breadcrumb($title);

$dbprefix = elgg_get_config('dbprefix');
$groupmemberships = elgg_get_entities_from_relationship(array(
	'relationship' => 'member',
	'relationship_guid' => $user->guid,
	'type' => 'group',
	'joins' => array("JOIN {$dbprefix}groups_entity ge ON e.guid = ge.guid"),
	'order_by' => 'ge.name ASC',
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
