<?php
/**
 * Admin control panel widget
 */

$items = [
	[
		'name' => 'flush',
		'text' => elgg_echo('admin:cache:flush'),
		'icon' => 'sync-alt',
		'href' => elgg_generate_action_url('admin/site/flush_cache'),
		'link_class' => 'elgg-button elgg-button-action',
	],
];

if (!_elgg_services()->mutex->isLocked('upgrade')) {
	$items[] = [
		'name' => 'upgrade',
		'text' => elgg_echo('upgrade'),
		'icon' => 'cogs',
		'href' => 'upgrade.php',
		'link_class' => 'elgg-button elgg-button-action',
	];
} else {
	$items[] = [
		'name' => 'unlock_upgrade',
		'text' => elgg_echo('upgrade:unlock'),
		'icon' => 'unlock',
		'href' => elgg_generate_action_url('admin/site/unlock_upgrade'),
		'link_class' => 'elgg-button elgg-button-action',
		'confirm' => elgg_echo('upgrade:unlock:confirm'),
	];
}

echo elgg_view_menu('admin_control_panel', [
	'class' => 'elgg-menu-hz',
	'item_class' => 'mrm',
	'items' => $items,
]);
