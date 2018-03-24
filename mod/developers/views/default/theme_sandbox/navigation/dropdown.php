<?php

$items = [
	[
		'name' => 'parent',
		'text' => 'Show Submenu',
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
		'child_menu' => [
			'display' => 'toggle',
		],
	];


	foreach (['A', 'B', 'C'] as $letter) {
		$items[] = [
			'name' => "$icon:$letter",
			'href' => '#',
			'text' => "Child $letter",
			'parent_name' => $icon,
			'child_menu' => [
				'display' => 'toggle',
			],
		];

		foreach (['AA', 'BB', 'CC'] as $subletter) {
			$items[] = [
				'name' => "$icon:$letter:$subletter",
				'href' => '#',
				'text' => "Child $subletter",
				'parent_name' => "$icon:$letter",
			];
		}
	}
}

echo elgg_view_menu('theme_sandbox:dropdown_menu', [
	'items' => $items,
	'class' => 'elgg-menu-hz',
]);

