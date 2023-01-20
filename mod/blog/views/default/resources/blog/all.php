<?php

$lower = elgg_extract('lower', $vars);
$upper = elgg_extract('upper', $vars);

elgg_register_title_button('add', 'object', 'blog');

elgg_push_collection_breadcrumbs('object', 'blog');

$title = elgg_echo('collection:object:blog:all');
if ($lower) {
	$title .= ': ' . elgg_echo('date:month:' . date('m', $lower), [date('Y', $lower)]);
}

echo elgg_view_page($title, [
	'content' => elgg_view('blog/listing/all', [
		'created_after' => $lower,
		'created_before' => $upper,
	]),
	'sidebar' => elgg_view('blog/sidebar', ['page' => 'all']),
	'filter_value' => 'all',
]);
