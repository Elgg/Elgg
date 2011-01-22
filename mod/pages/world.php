<?php
/**
 * List all pages
 *
 * @package ElggPages
 */

$title = elgg_echo('pages:all');

elgg_pop_breadcrumb();
elgg_push_breadcrumb(elgg_echo('pages'));

$content = elgg_list_entities(array(
	'types' => 'object',
	'subtypes' => 'page_top',
	'full_view' => false,
));

$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
));

echo elgg_view_page($title, $body);
