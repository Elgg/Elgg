<?php
/**
 * Notification settings page
 */

$user = elgg_get_page_owner_entity();

// Set the context to settings
elgg_set_context('settings');

elgg_push_breadcrumb(elgg_echo('settings'), elgg_generate_url('settings:account', ['username' => $user->username]));

echo elgg_view_page(elgg_echo('usersettings:notifications:title'), [
	'content' => elgg_view_form('settings/notifications', [], [
		'entity' => $user,
	]),
	'show_owner_block_menu' => false,
	'filter_id' => 'settings/notifications',
	'filter_value' => 'settings',
]);
