<?php

elgg_push_collection_breadcrumbs('object', 'discussion');

$title = elgg_echo('discussion:latest');
$content = elgg_view('discussion/listing/all');

$body = elgg_view_layout('content', [
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('discussion/sidebar'),
	'filter' => '',
]);

echo elgg_view_page($title, $body);
