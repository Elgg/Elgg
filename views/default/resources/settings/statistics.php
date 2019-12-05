<?php
/**
 * Elgg user statistics.
 */

$username = elgg_extract('username', $vars);
if (!$username) {
	$username = elgg_get_logged_in_user_entity()->username;
}

$user = get_user_by_username($username);
if (!$user || !$user->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

elgg_set_page_owner_guid($user->guid);

elgg_push_breadcrumb(elgg_echo('settings'), elgg_generate_url('settings:account', [
	'username' => $user->username,
]));

echo elgg_view_page(elgg_echo('usersettings:statistics'), [
	'content' => elgg_view('core/settings/statistics'),
	'show_owner_block_menu' => false,
]);
