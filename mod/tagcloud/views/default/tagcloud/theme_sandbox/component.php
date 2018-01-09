<?php
$tags = [
	(object) ['tag' => 'php', 'total' => 2],
	(object) ['tag' => 'elgg', 'total' => 8],
	(object) ['tag' => 'javascript', 'total' => 3],
	(object) ['tag' => 'css', 'total' => 4],
	(object) ['tag' => 'html', 'total' => 1],
	(object) ['tag' => 'framework', 'total' => 4],
	(object) ['tag' => 'social', 'total' => 3],
	(object) ['tag' => 'web', 'total' => 7],
	(object) ['tag' => 'code', 'total' => 2],
];

$body = '<div style="width: 200px;">';
$body .= elgg_view('output/tagcloud', ['value' => $tags]);
$body .= '</div>';

echo elgg_view_module('aside', 'Tag cloud (.elgg-tagcloud)', $body);
