<?php
/**
 * List all pages
 *
 * @package ElggPages
 */

$title = elgg_echo('collection:object:page:all');

elgg_pop_breadcrumb();
elgg_push_breadcrumb(elgg_echo('collection:object:page'));

elgg_register_title_button('pages', 'add', 'object', 'page');

$content = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'page',
	'metadata_name_value_pairs' => [
		'parent_guid' => 0,
	],
	'full_view' => false,
	'no_results' => elgg_echo('pages:none'),
	'preload_owners' => true,
	'preload_containers' => true,
]);

$body = elgg_view_layout('content', [
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('pages/sidebar'),
]);

echo elgg_view_page($title, $body);
