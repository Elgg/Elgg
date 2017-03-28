<?php

$page_type = elgg_extract('page_type', $vars);

$listing = [
	'identifier' => 'blog',
	'type' => 'all',
	'entity_type' => 'object',
	'entity_subtype' => 'blog',
];

echo elgg_view_listing_page($listing, [
	'no_results' => elgg_echo('blog:none'),
], [
	'sidebar' => elgg_view('blog/sidebar', [
		'page' => $page_type,
	]),
]);