<?php
/**
 * All files
 */

elgg_push_collection_breadcrumbs('object', 'file');

elgg_register_title_button('file', 'add', 'object', 'file');

echo elgg_view_page(elgg_echo('collection:object:file:all'), [
	'filter_value' => 'all',
	'content' => elgg_view('file/listing/all', $vars),
	'sidebar' => elgg_view('file/sidebar', $vars),
]);
