<?php
/**
 * Manage user notification subscriptions
 */

$user = elgg_get_page_owner_entity();

// Set the context to settings
elgg_set_context('settings');

elgg_push_breadcrumb(elgg_echo('settings'), elgg_generate_url('settings:account', ['username' => $user->username]));

echo elgg_view_page(elgg_echo('usersettings:notifications:users:title'), [
	'content' => elgg_view_form('settings/notifications/users', [
		'action' => 'action/settings/notifications/subscriptions',
	], [
		'entity' => $user,
	]),
	'show_owner_block_menu' => false,
	'filter_id' => 'settings/notifications',
	'filter_value' => 'users',
]);
