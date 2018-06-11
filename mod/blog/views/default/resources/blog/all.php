<?php

$lower = elgg_extract('lower', $vars);
$upper = elgg_extract('upper', $vars);

elgg_register_title_button('blog', 'add', 'object', 'blog');

elgg_push_collection_breadcrumbs('object', 'blog');

$title = elgg_echo('collection:object:blog:all');
if ($lower) {
	$title .= ': ' . elgg_echo('date:month:' . date('m', $lower), [date('Y', $lower)]);
}

$content = elgg_view('blog/listing/all', [
	'created_after' => $lower,
	'created_before' => $upper,
]);

$layout = elgg_view_layout('default', [
	'title' => $title,
	'content' => $content,
	'sidebar' => elgg_view('blog/sidebar', ['page' => 'all']),
	'filter_value' => 'all',
]);

echo elgg_view_page($title, $layout);
