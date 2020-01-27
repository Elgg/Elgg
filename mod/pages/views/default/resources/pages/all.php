<?php
/**
 * List all pages
 */

elgg_push_collection_breadcrumbs('object', 'page');

elgg_register_title_button('pages', 'add', 'object', 'page');

echo elgg_view_page(elgg_echo('collection:object:page:all'), [
	'filter_value' => 'all',
	'content' => elgg_view('pages/listing/all', $vars),
	'sidebar' => elgg_view('pages/sidebar', $vars),
]);
