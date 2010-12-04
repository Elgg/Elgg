<?php
/**
 * Display sidebar tagcloud
 **/

// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

$title = elgg_view_title(elgg_echo('tagcloud:site:title'));

$options = array(
	'threshold' => 0,
	'limit' => 100,
	'tag_name' => 'tags',
);
$tags = elgg_view_tagcloud($options);

$params = array(
	'content' => $title . $tags,
	'sidebar' => $sidebar
);
$body = elgg_view_layout("one_column_with_sidebar", $params);

echo elgg_view_page(elgg_echo('tagcloud:site:title', array($page_owner->name)), $body);