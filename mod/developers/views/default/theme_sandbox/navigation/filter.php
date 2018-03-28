<?php

$items = [];

foreach (['heart-o', 'star-o', 'bell-o'] as $icon) {
	$items[] = [
		'name' => $icon,
		'icon' => $icon,
		'text' => "Menu item",
		'href' => '#',
		'child_menu' => [
			'display' => 'dropdown',
			'data-position' => json_encode([
				'my' => 'left top',
				'at' => 'left bottom+8px',
				'collision' => 'fit fit',
			]),
		],
		'selected' => $icon == 'heart-o',
	];
}

foreach (['A', 'B', 'C'] as $letter) {
	$items[] = [
		'name' => "bell-o:$letter",
		'href' => '#',
		'text' => "Child $letter",
		'parent_name' => $icon,
		'child_menu' => [
			'display' => 'toggle',
		],
	];

	foreach (['AA', 'BB', 'CC'] as $subletter) {
		$items[] = [
			'name' => "bell-o:$letter:$subletter",
			'href' => '#',
			'text' => "Child $subletter",
			'parent_name' => "$icon:$letter",
		];
	}
}

echo elgg_view_menu('filter', [
	'items' => $items,
]);