<?php
/**
 * List all pages
 *
 * @package ElggPages
 */

$title = elgg_echo('pages:all');

elgg_pop_breadcrumb();
elgg_push_breadcrumb(elgg_echo('pages'));

elgg_register_title_button();

$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'page_top',
	'full_view' => false,
	'no_results' => elgg_echo('pages:none'),
));

$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('pages/sidebar'),
));

echo elgg_view_page($title, $body);
