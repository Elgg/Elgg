<?php
/**
 * List all pages
 *
 * @package ElggPages
 */

$title = elgg_echo('collection:object:page:all');

elgg_push_collection_breadcrumbs('object', 'page');

elgg_register_title_button('pages', 'add', 'object', 'page');

$content = elgg_view('pages/listing/all', $vars);

$body = elgg_view_layout('default', [
	'filter_value' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('pages/sidebar', $vars),
]);

echo elgg_view_page($title, $body);
