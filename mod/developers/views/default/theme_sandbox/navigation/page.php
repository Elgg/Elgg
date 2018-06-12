<?php

$items = [];

foreach (['cogs', 'support', 'question', 'info'] as $index => $icon) {
	$items[] = [
		'name' => $icon,
		'icon' => $icon,
		'badge' => $index ?: null,
		'text' => "Menu item",
		'href' => '#',
		'link_class' => $icon == 'info' ? 'elgg-state elgg-state-notice' : '',
	];


	foreach (['A', 'B', 'C'] as $letter) {
		$items[] = [
			'name' => "$icon:$letter",
			'href' => '#',
			'text' => "Child $letter",
			'parent_name' => $icon,
			'link_class' => $icon == 'info' ? 'elgg-state elgg-state-notice' : '',
		];

		foreach (['AA', 'BB', 'CC'] as $subletter) {
			$items[] = [
				'name' => "$icon:$letter:$subletter",
				'href' => '#',
				'text' => "Child $subletter",
				'parent_name' => "$icon:$letter",
				'link_class' => $icon == 'info' ? 'elgg-state elgg-state-notice' : '',
			];
		}
	}
}

echo elgg_view_menu('page', [
	'items' => $items,
]);