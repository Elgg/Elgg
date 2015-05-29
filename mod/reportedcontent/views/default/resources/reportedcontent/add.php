<?php
elgg_gatekeeper();

$title = elgg_echo('reportedcontent:this');

$content = elgg_view_form('reportedcontent/add');
$sidebar = elgg_echo('reportedcontent:instructions');

$params = array(
	'title' => $title,
	'content' => $content,
	'sidebar' => $sidebar,
);
$body = elgg_view_layout('one_sidebar', $params);

echo elgg_view_page($title, $body);