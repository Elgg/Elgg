<?php

$toggle = elgg_view_icon('plus', ['class' => 'elgg-state-closed']);
$toggle .= elgg_view_icon('minus', ['class' => 'elgg-state-opened']);

$items = [
	[
		'name' => 'parent',
		'text' => $toggle . 'Show Submenu',
		'href' => '#',
		'child_menu' => [
			'display' => 'toggle',
			'data-toggle-duration' => 500,
		],
	],
];

foreach (['anchor', 'binoculars', 'building', 'birthday-cake'] as $icon) {
	$items[] = [
		'name' => $icon,
		'icon' => $icon,
		'text' => "Menu item",
		'href' => '#',
		'parent_name' => 'parent',
	];
}

echo elgg_view_menu('theme_sandbox:dropdown_menu', [
	'items' => $items,
	'class' => 'elgg-menu-hz',
]);

