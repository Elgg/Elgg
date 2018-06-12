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

if (elgg_is_admin_logged_in() && $admin) {
	$actions[] = \ElggMenuItem::factory([
		'name' => 'toggle_admin',
		'text' => elgg_echo('admin:options'),
		'icon' => 'ellipsis-v',
		'href' => '#',
		'data-toggle-selector' => ".hover_toggle_admin_{$user->guid}",
		'rel' => 'toggle',
	]);
}

$user_info = elgg_view_entity($user, [
	'full_view' => false,
	'use_hover' => false,
	'size' => 'medium',
	'metadata' => false,
]);

$card = elgg_format_element('div', ['class' => 'elgg-menu-hover-card'], $user_info);

// actions
if (elgg_is_logged_in() && $actions) {
	$card .= elgg_view('navigation/menu/elements/section', [
		'class' => "elgg-menu elgg-menu-hover-actions",
		'items' => $actions,
	]);
}

// main
if ($main) {
	$card .= elgg_view('navigation/menu/elements/section', [
		'class' => 'elgg-menu elgg-menu-hover-default',
		'items' => $main,
	]);
}

echo elgg_format_element('div', ['class' => 'elgg-menu-hover-card-container'], $card);

// admin
if (elgg_is_admin_logged_in() && $admin) {
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
