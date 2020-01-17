<?php
/**
 * Elgg notifications plugin group index
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

elgg_push_breadcrumb(elgg_echo('settings'), elgg_generate_url('settings:account', ['username' => $user->username]));

echo elgg_view_page(elgg_echo('notifications:subscriptions:changesettings:groups'), [
	'content' => elgg_view('notifications/groups', [
		'user' => $user,
	]),
	'show_owner_block_menu' => false,
]);
