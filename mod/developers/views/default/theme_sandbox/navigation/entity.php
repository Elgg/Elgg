<?php

$entity = new ElggObject();
$entity->subtype = 'custom';
$entity->title = 'Hello, world!';

$items = [];

foreach (['star', 'trash'] as $icon) {
	$items[] = [
		'name' => $icon,
		'icon' => $icon,
		'text' => "Menu item",
		'href' => '#',
		'link_class' => $icon == 'trash' ? 'elgg-state elgg-state-danger' : '',
		'child_menu' => [
			'display' => 'toggle',
		]
	];

	foreach (['A', 'B'] as $letter) {
		$items[] = [
			'name' => "$icon:$letter",
			'href' => '#',
			'text' => "Child $letter",
			'parent_name' => $icon,
			'link_class' => $icon == 'trash' ? 'elgg-state elgg-state-danger' : '',
		];
	}
}


$metadata = elgg_view_menu('entity', [
	'items' => $items,
	'entity' => $entity,
]);

echo elgg_view('object/elements/summary', [
	'entity' => $entity,
	'metadata' => $metadata,
]);