<?php

elgg_push_collection_breadcrumbs('object', 'discussion');

$title = elgg_echo('discussion:latest');
$content = elgg_view('discussion/listing/all');

$body = elgg_view_layout('default', [
	'content' => $content,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
