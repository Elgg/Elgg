<?php

$items = [];

foreach (['bell', 'bank', 'coffee', 'trash'] as $icon) {
	$items[] = [
		'name' => $icon,
		'icon' => $icon,
		'text' => "Menu item",
		'href' => '#',
		'link_class' => $icon == 'trash' ? 'elgg-state elgg-state-danger' : '',
	];

	foreach (['A', 'B', 'C'] as $letter) {
		$items[] = [
			'name' => "$icon:$letter",
			'href' => '#',
			'text' => "Child $letter",
			'parent_name' => $icon,
			'link_class' => $icon == 'trash' ? 'elgg-state elgg-state-danger' : '',
		];

		foreach (['AA', 'BB', 'CC'] as $subletter) {
			$items[] = [
				'name' => "$icon:$letter:$subletter",
				'href' => '#',
				'text' => "Child $subletter",
				'parent_name' => "$icon:$letter",
				'link_class' => $icon == 'trash' ? 'elgg-state elgg-state-danger' : '',
			];
		}
	}
}

echo elgg_view_menu('owner_block', [
	'items' => $items,
	'prepare_vertical' => true,
]);
