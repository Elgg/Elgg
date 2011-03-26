<?php
/**
 * All wire posts
 * 
 */

elgg_push_breadcrumb(elgg_echo('thewire'));

$title = elgg_echo('thewire:everyone');

if (elgg_is_logged_in()) {
	$content .= elgg_view_form('thewire/add');
	$content .= elgg_view('input/urlshortener');
}

$content .= elgg_list_entities(array(
	'type' => 'object',
	'subtype' => 'thewire',
	'limit' => 15,
));

$body = elgg_view_layout('content', array(
	'filter_context' => 'all',
	'content' => $content,
	'title' => $title,
	'buttons' => false,
	'sidebar' => elgg_view('thewire/sidebar'),
));

echo elgg_view_page($title, $body);
