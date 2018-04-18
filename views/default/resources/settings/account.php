<?php
/**
 * Elgg user account settings.
 *
 * @package Elgg
 * @subpackage Core
 */

elgg_gatekeeper();

$username = elgg_extract('username', $vars);
if (!$username) {
	$username = elgg_get_logged_in_user_entity()->username;
}

$user = get_user_by_username($username);
if (!$user || !$user->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

elgg_set_page_owner_guid($user->guid);

elgg_push_breadcrumb(elgg_echo('settings'), "settings/user/$username");

$title = elgg_echo('usersettings:user', [$user->getDisplayName()]);

$content = elgg_view('core/settings/account', [
	'entity' => $user,
]);

$params = [
	'content' => $content,
	'title' => $title,
	'show_owner_block_menu' => false,
];
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);
