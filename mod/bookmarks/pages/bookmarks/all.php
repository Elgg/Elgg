<?php
/**
 * Elgg bookmarks plugin everyone page
 *
 * @package ElggBookmarks
 */

$offset = (int)get_input('offset', 0);
$content = elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'bookmarks',
	'limit' => 10,
	'offset' => $offset,
	'full_view' => false,
	'view_toggle_type' => false
));

$title = elgg_echo('bookmarks:everyone');

$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('bookmarks/sidebar'),
));

echo elgg_view_page($title, $body);