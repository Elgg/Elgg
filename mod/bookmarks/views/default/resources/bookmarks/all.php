<?php
/**
 * Elgg bookmarks plugin everyone page
 */

elgg_push_collection_breadcrumbs('object', 'bookmarks');

elgg_register_title_button('bookmarks', 'add', 'object', 'bookmarks');

echo elgg_view_page(elgg_echo('collection:object:bookmarks:all'), [
	'filter_id' => 'filter',
	'filter_value' => 'all',
	'content' => elgg_view('bookmarks/listing/all', $vars),
	'sidebar' => elgg_view('bookmarks/sidebar', $vars),
]);
