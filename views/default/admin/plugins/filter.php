<?php
/**
 * Category filter for plugins
 *
 * @uses $vars['category_options']
 */

$categories = elgg_extract('category_options', $vars);
if (empty($categories)) {
	return;
}

$items = [];

foreach ($categories as $key => $category) {
	if (empty($key)) {
		continue;
	}

	$key = preg_replace('/[^a-z0-9-]/i', '-', $key);
	$items[] = [
		'name' => "category$key",
		'text' => $category,
		'href' => '#',
		'rel' => $key
	];
}

$body = elgg_view_menu('plugins:filter', [
	'items' => $items,
	'class' => 'elgg-admin-plugins-categories elgg-admin-sidebar-menu elgg-menu-hz nav flex-column',
]);

echo elgg_view_module('', elgg_echo('filter'), $body, [
	'id' => 'plugins-filter',
]);
