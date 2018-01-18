<?php
/**
 * Elgg notifications plugin index
 *
 * @package ElggNotifications
 *
 * @uses $user ElggUser
 */

$user = elgg_get_page_owner_entity();
if (!$user instanceof ElggUser) {
	$user = elgg_get_logged_in_user_entity();
}

if (!$user instanceof ElggUser || !$user->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

elgg_set_page_owner_guid($user->guid);

// Set the context to settings
elgg_set_context('settings');

$title = elgg_echo('notifications:subscriptions:changesettings');

elgg_push_breadcrumb(elgg_echo('settings'), "settings/user/{$user->username}");

$content = elgg_view('notifications/personal', [
	'user' => $user,
]);

$layout = elgg_view_layout('one_sidebar', [
	'content' => $content,
	'title' => $title,
	'show_owner_block_menu' => false,
]);

echo elgg_view_page($title, $layout);
