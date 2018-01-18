<?php
/**
 * Elgg bookmarks plugin everyone page
 *
 * @package ElggBookmarks
 */

elgg_push_collection_breadcrumbs('object', 'bookmarks');

elgg_register_title_button('bookmarks', 'add', 'object', 'bookmarks');

$content = elgg_view('bookmarks/listing/all', $vars);

$title = elgg_echo('collection:object:bookmarks:all');

$body = elgg_view_layout('default', [
	'filter_id' => 'filter',
	'filter_value' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('bookmarks/sidebar', $vars),
]);

echo elgg_view_page($title, $body);
