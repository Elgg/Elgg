<?php

elgg_gatekeeper();

elgg_load_library('elgg:blog');

$page_type = elgg_extract('page_type', $vars);
$guid = elgg_extract('guid', $vars);

$params = blog_get_page_content_edit('add', $guid);

if (isset($params['sidebar'])) {
	$params['sidebar'] .= elgg_view('blog/sidebar', ['page' => $page_type]);
} else {
	$params['sidebar'] = elgg_view('blog/sidebar', ['page' => $page_type]);
}

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
