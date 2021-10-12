<?php

elgg_set_page_owner_guid(elgg_get_logged_in_user_guid());

// add control panel menu items to title menu
$items = elgg()->menus->getUnpreparedMenu('admin_control_panel')->getItems();
foreach ($items as $menu_item) {
	elgg_register_menu_item('title', $menu_item);
}

echo elgg_view_layout('widgets', [
	'num_columns' => 2,
	'show_access' => false,
	'owner_guid' => elgg_get_logged_in_user_guid(),
]);
