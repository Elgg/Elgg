<?php
/**
 * Admin control panel widget
 */

elgg_register_menu_item('admin_control_panel', array(
	'name' => 'flush',
	'text' => elgg_echo('admin:cache:flush'),
	'href' => 'action/admin/site/flush_cache',
	'is_action' => true,
	'link_class' => 'elgg-button elgg-button-action',
));

elgg_register_menu_item('admin_control_panel', array(
	'name' => 'upgrade',
	'text' => elgg_echo('upgrade'),
	'href' => 'upgrade.php',
	'link_class' => 'elgg-button elgg-button-action',
));

echo elgg_view_menu('admin_control_panel', array(
	'class' => 'elgg-menu-hz',
	'item_class' => 'mrm',
));
