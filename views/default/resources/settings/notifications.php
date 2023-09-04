<?php
/**
 * Notification settings page
 */

echo elgg_view_page(elgg_echo('usersettings:notifications:title'), [
	'content' => elgg_view_form('settings/notifications', [], [
		'entity' => elgg_get_page_owner_entity(),
	]),
	'show_owner_block_menu' => false,
	'filter_id' => 'settings/notifications',
	'filter_value' => 'settings',
]);
