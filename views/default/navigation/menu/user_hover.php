<?php
/**
 * User hover menu
 *
 * Register for the 'register', 'menu:user_hover' plugin hook to add to the user
 * hover menu. There are three sections: action, default, and admin.
 *
 * @uses $vars['menu']      Menu array provided by elgg_view_menu()
 */

$menu = elgg_extract('menu', $vars);
if (!$menu instanceof \Elgg\Menu\PreparedMenu) {
	return;
}

$actions = $menu->getItems('action');
$main = $menu->getItems('default');
$admin = $menu->getItems('admin');

$user = elgg_extract('entity', $vars);
if (!($user instanceof ElggUser)) {
	return;
}

elgg_push_context('user_hover');

$user_info = elgg_view_entity($user, [
	'full_view' => false,
	'use_hover' => false,
	'size' => 'medium',
	'metadata' => false,
]);

$card = elgg_format_element('div', ['class' => 'elgg-menu-hover-card'], $user_info);

// actions
$combined_actions = [];
if (elgg_is_logged_in() && !empty($actions)) {
	$combined_actions += $actions;
}

// main
if (!empty($main)) {
	$combined_actions += $main;
}

if (elgg_is_admin_logged_in() && !empty($admin)) {
	$combined_actions[] = \ElggMenuItem::factory([
		'name' => 'toggle_admin',
		'text' => elgg_echo('admin:options'),
		'icon' => 'ellipsis-v',
		'href' => false,
		'data-toggle-selector' => ".hover_toggle_admin_{$user->guid}",
		'rel' => 'toggle',
	]);
}

if (!empty($combined_actions)) {
	$card .= elgg_view('navigation/menu/elements/section', [
		'class' => "elgg-menu elgg-menu-hover-actions",
		'items' => $combined_actions,
	]);
}

echo elgg_format_element('div', ['class' => 'elgg-menu-hover-card-container'], $card);

// admin
if (elgg_is_admin_logged_in() && !empty($admin)) {
	echo elgg_view('navigation/menu/elements/section', [
		'class' => [
			'elgg-menu',
			'elgg-menu-hover-admin',
			'hidden',
			"hover_toggle_admin_{$user->guid}",
		],
		'items' => $admin,
	]);
}

elgg_pop_context();
