<?php

$items = [
	[
		'name' => "hz1",
		'href' => '#',
		'text' => "The first item",
	],
	[
		'name' => "hz2",
		'href' => '#',
		'text' => "Item with badge",
		'badge' => 3,
	],
	[
		'name' => "hz3",
		'href' => '#',
		'text' => "Item with icon",
		'icon' => "user",
	],
	[
		'name' => "hz3",
		'href' => '#',
		'text' => "Item with badge and icon",
		'icon' => "user",
		'badge' => "33",
	],
];

$menu = elgg_view_menu('horizontal', [
	'items' => $items,
	'class' => 'elgg-menu-hz',
]);

echo elgg_view_module('info', 'Module with simple horizontal menu', $menu, ['menu' => $menu]);
