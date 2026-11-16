<?php

elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

// add control panel menu items to title menu
$items = elgg()->menus->getUnpreparedMenu('admin_control_panel')->getItems();
foreach ($items as $menu_item) {
	elgg_register_menu_item('title', $menu_item);
}

$unfinished_upgrades_count = elgg_count_entities([
	'type' => 'object',
	'subtype' => 'elgg_upgrade',
	'metadata_name_value_pairs' => ['is_completed' => false],
]);

if ($unfinished_upgrades_count) {
	echo elgg_view_message('notice', elgg_echo('admin:pending_upgrades'), [
		'menu' => elgg_view_url(elgg_generate_url('admin', ['segments' => 'upgrades']), elgg_echo('admin:view_upgrades'))
	]);
}

echo elgg_view_layout('widgets', [
	'num_columns' => 2,
	'show_access' => false,
	'owner_guid' => elgg_get_logged_in_user_guid(),
]);
