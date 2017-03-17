<?php

$title = elgg_echo('tagcloud:site_cloud');
$options = [
	'threshold' => 0,
	'limit' => 100,
	'tag_name' => 'tags',
];

$content = elgg_view_tagcloud($options);

$body = elgg_view_layout('one_sidebar', [
	'title' => $title,
	'content' => $content,
]);

echo elgg_view_page($title, $body);
