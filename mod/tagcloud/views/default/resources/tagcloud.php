<?php

$title = elgg_echo('tagcloud:site_cloud');

$content = elgg_view_tagcloud([
	'threshold' => 0,
	'limit' => 100,
	'tag_name' => 'tags',
]);

$body = elgg_view_layout('one_sidebar', [
	'title' => $title,
	'content' => $content,
]);

echo elgg_view_page($title, $body);
