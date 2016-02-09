<?php
/**
 * Elgg notifications plugin index
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
	$url = 'notifications/personal/' . elgg_get_logged_in_user_entity()->username;
	forward($url);
}

elgg_set_page_owner_guid($user->guid);

$title = elgg_echo('notifications:subscriptions:changesettings');

elgg_push_breadcrumb(elgg_echo('settings'), "settings/user/$user->username");
elgg_push_breadcrumb($title);

// Get the form
$people = array();
if ($people_ents = elgg_get_entities_from_relationship(array(
		'relationship' => 'notify',
		'relationship_guid' => $user->guid,
		'type' => 'user',
		'limit' => false,
	))) {
	
	foreach($people_ents as $ent) {
		$people[] = $ent->guid;
	}
}

$body = elgg_view('notifications/subscriptions/form', array(
	'people' => $people,
	'user' => $user,
));

$params = array(
	'content' => $body,
	'title' => $title,
);
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
