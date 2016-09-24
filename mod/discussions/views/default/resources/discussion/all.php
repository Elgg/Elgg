<?php

elgg_pop_breadcrumb();
elgg_push_breadcrumb(elgg_echo('discussion'));

$content = elgg_view('discussion/listing/all');

$title = elgg_echo('discussion:latest');

$params = array(
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('discussion/sidebar'),
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);