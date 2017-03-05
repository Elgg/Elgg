<?php

$toggle = elgg_view_icon('chevron-up', ['class' => 'elgg-state-closed']);
$toggle .= elgg_view_icon('chevron-down', ['class' => 'elgg-state-opened']);

$items = [
	[
		'name' => 'parent',
		'text' => 'Show Submenu' . $toggle,
		'href' => '#',
		'child_menu' => [
			'display' => 'dropdown',
			'data-position' => json_encode([
				'at' => 'right top',
				'my' => 'right bottom',
				'collision' => 'fit fit',
			]),
		],
	],
];

foreach (['bell', 'bank', 'coffee', 'car'] as $icon) {
	$items[] = [
		'name' => $icon,
		'icon' => $icon,
		'text' => "Menu item",
		'href' => '#',
		'parent_name' => 'parent',
	];
}

echo elgg_view_menu('theme_sandbox:dropdown_menu', [
	'sort_by' => 'priority',
	'items' => $items,
	'class' => 'elgg-menu-hz',
]);

