<?php
$tags = array(
	(object)array('tag' => 'php', 'total' => 2),
	(object)array('tag' => 'elgg', 'total' => 8),
	(object)array('tag' => 'javascript', 'total' => 3),
	(object)array('tag' => 'css', 'total' => 4),
	(object)array('tag' => 'html', 'total' => 1),
	(object)array('tag' => 'framework', 'total' => 4),
	(object)array('tag' => 'social', 'total' => 3),
	(object)array('tag' => 'web', 'total' => 7),
	(object)array('tag' => 'code', 'total' => 2),
);

$body = '<div style="width: 200px;">';
$body .= elgg_view('output/tagcloud', array('value' => $tags));
$body .= '</div>';

echo elgg_view_module('theme-sandbox-demo', 'Tag cloud (.elgg-tagcloud)', $body);
