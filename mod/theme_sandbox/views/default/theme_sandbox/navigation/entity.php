<?php

$entity = new \ThemeSandboxObject();
$entity->setSubtype('custom');
$entity->title = 'Hello, world!';

$items = [];

foreach (['star', 'trash'] as $icon) {
	$items[] = [
		'name' => $icon,
		'icon' => $icon,
		'text' => 'Menu item',
		'href' => false,
		'link_class' => $icon == 'trash' ? 'elgg-state elgg-state-danger' : '',
	];

	foreach (['A', 'B'] as $letter) {
		$items[] = [
			'name' => "{$icon}:{$letter}",
			'href' => false,
			'text' => "Child {$letter}",
			'parent_name' => $icon,
			'link_class' => $icon == 'trash' ? 'elgg-state elgg-state-danger' : '',
		];
	}
}


$metadata = elgg_view_menu('entity', [
	'items' => $items,
	'entity' => $entity,
	'prepare_dropdown' => true,
]);

echo elgg_view('object/elements/summary', [
	'entity' => $entity,
	'metadata' => $metadata,
]);
