<?php

$active_filter = elgg_extract('active_filter', $vars);
$categories = (array) elgg_extract('categories', $vars);
if (empty($categories)) {
	return;
}

elgg_require_css('admin/plugins/categories');
elgg_import_esm('admin/plugins/categories');

asort($categories);

$common_categories = [
	'all' => elgg_echo('admin:plugins:category:all'),
	'active' => elgg_echo('admin:plugins:category:active'),
	'inactive' => elgg_echo('admin:plugins:category:inactive'),
];

$categories = array_merge($common_categories, $categories);

$list_items = '';
foreach ($categories as $key => $category) {
	if (empty($key)) {
		continue;
	}
	
	$key = preg_replace('/[^a-z0-9-]/i', '-', elgg_strtolower($key));
	
	$list_items .= elgg_view('output/url', [
		'href' => false,
		'text' => $category,
		'rel' => $key,
		'class' => ($key === $active_filter) ? 'elgg-state-selected' : null,
	]);
}

$body = elgg_format_element('div', ['class' => 'elgg-admin-plugins-categories'], $list_items);

echo elgg_view_module('', elgg_echo('filter'), $body);
