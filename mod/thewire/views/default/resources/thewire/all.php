<?php
/**
 * All wire posts
 */

elgg_push_collection_breadcrumbs('object', 'thewire');

$title = elgg_echo('collection:object:thewire:all');

$content = '';
if (elgg_is_logged_in()) {
	$content .= elgg_view_form('thewire/add', [
		'class' => 'thewire-form',
		'prevent_double_submit' => true,
	]);
	$content .= elgg_view('input/urlshortener');
}

$content .= elgg_list_entities([
	'type' => 'object',
	'subtype' => 'thewire',
	'limit' => get_input('limit', 15),
]);

$body = elgg_view_layout('content', [
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('thewire/sidebar'),
]);

echo elgg_view_page($title, $body);
