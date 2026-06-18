<?php

$items = [
	[
		'name' => 'parent',
		'text' => 'Show Submenu',
		'href' => false,
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
		'text' => 'Menu item',
		'href' => false,
		'parent_name' => 'parent',
	];


	foreach (['A', 'B', 'C'] as $letter) {
		$items[] = [
			'name' => "{$icon}:{$letter}",
			'href' => false,
			'text' => "Child {$letter}",
			'parent_name' => $icon,
		];

		foreach (['AA', 'BB', 'CC'] as $subletter) {
			$items[] = [
				'name' => "{$icon}:{$letter}:{$subletter}",
				'href' => false,
				'text' => "Child {$subletter}",
				'parent_name' => "{$icon}:{$letter}",
			];
		}
	}
}

echo elgg_view_menu('theme_sandbox:dropdown_menu', [
	'items' => $items,
	'class' => 'elgg-menu-hz',
]);
