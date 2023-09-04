<?php
/**
 * Manage user notification subscriptions
 */

echo elgg_view_page(elgg_echo('usersettings:notifications:users:title'), [
	'content' => elgg_view_form('settings/notifications/users', [
		'action' => 'action/settings/notifications/subscriptions',
	], [
		'entity' => elgg_get_page_owner_entity(),
	]),
	'show_owner_block_menu' => false,
	'filter_id' => 'settings/notifications',
	'filter_value' => 'users',
]);
