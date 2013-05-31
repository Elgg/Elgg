<?php
/**
 * All files
 *
 * @package ElggFile
 */

elgg_push_breadcrumb(elgg_echo('file'));

elgg_register_title_button();

$title = elgg_echo('file:all');

$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'file',
	'full_view' => FALSE
));
if (!$content) {
	$content = elgg_echo('file:none');
}

$sidebar = file_get_type_cloud();
$sidebar = elgg_view('file/sidebar');

$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => $sidebar,
));

echo elgg_view_page($title, $body);
