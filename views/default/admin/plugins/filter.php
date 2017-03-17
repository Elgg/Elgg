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

$list_items = '';
foreach ($categories as $key => $category) {
	if (empty($key)) {
		continue;
	}

	$key = preg_replace('/[^a-z0-9-]/i', '-', $key);
	$link = elgg_view('output/url', [
		'text' => $category,
		'href' => '#',
		'rel' => $key
	]);

	$list_items .= elgg_format_element('li', [], $link);
}

$body = elgg_format_element([
	'#tag_name' => 'ul',
	'class' => 'elgg-admin-plugins-categories elgg-admin-sidebar-menu elgg-menu-hz',
	'#text' => $list_items,
]);

echo elgg_view_module('', elgg_echo('filter'), $body, [
	'id' => 'plugins-filter',
]);
