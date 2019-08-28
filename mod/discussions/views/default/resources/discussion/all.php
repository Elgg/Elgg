<?php

elgg_push_collection_breadcrumbs('object', 'discussion');

$title = elgg_echo('discussion:latest');
$content = elgg_view('discussion/listing/all');

$body = elgg_view_layout('default', [
	'title' => $title,
	'content' => $content,
]);

echo elgg_view_page($title, $body);
