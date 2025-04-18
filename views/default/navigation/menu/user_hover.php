<?php
/**
 * User hover menu
 *
 * Register for the 'register', 'menu:user_hover' plugin hook to add to the user
 * hover menu. There are three sections: action, default, and admin.
 *
 * @uses $vars['menu']      Menu array provided by elgg_view_menu()
 */

$user = elgg_extract('entity', $vars);
if (!$user instanceof \ElggUser) {
	return;
}

$menu = elgg_extract('menu', $vars);
if (!$menu instanceof \Elgg\Menu\PreparedMenu) {
	return;
}

$actions = $menu->getItems('action');
$main = $menu->getItems('default');
$admin = $menu->getItems('admin');

elgg_push_context('user_hover');

$card = elgg_format_element('div', ['class' => 'elgg-menu-hover-card'], elgg_view_entity($user, [
	'full_view' => false,
	'use_hover' => false,
	'size' => 'medium',
	'metadata' => false,
]));

$combined_actions = elgg_is_logged_in() ? $actions + $main : $main;
if (!empty($combined_actions)) {
	$card .= elgg_view_menu(new \Elgg\Menu\UnpreparedMenu(['name' => 'hover_actions'], $combined_actions));
}

echo elgg_format_element('div', ['class' => 'elgg-menu-hover-card-container'], $card);

if (elgg_is_admin_logged_in() && !empty($admin)) {
	$admin_toggle = [
		\ElggMenuItem::factory([
			'name' => 'toggle_admin',
			'icon' => 'user-cog',
			'text' => elgg_echo('admin:options'),
			'href' => false,
			'child_menu' => [
				'display' => 'toggle',
			],
		]),
	];
	
	foreach ($admin as $admin_item) {
		$admin_item->setSection('default');
		if (empty($admin_item->getParentName())) {
			$admin_item->setParentName('toggle_admin');
		}
	}
	
	echo elgg_view_menu(new \Elgg\Menu\UnpreparedMenu(['name' => 'hover_admin'], $admin_toggle + $admin));
}

elgg_pop_context();
