<?php
/**
 * All files
 *
 * @package ElggFile
 */

elgg_push_breadcrumb(elgg_echo('file'));

$limit = get_input("limit", 10);

$title = elgg_echo('file:all');

$content = elgg_list_entities(array(
	'types' => 'object',
	'subtypes' => 'file',
	'limit' => $limit,
	'full_view' => FALSE
));

$sidebar = file_get_type_cloud();
$sidebar = elgg_view('file/sidebar');

$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));

echo elgg_view_page($title, $body);
