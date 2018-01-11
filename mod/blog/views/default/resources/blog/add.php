<?php

elgg_gatekeeper();

elgg_load_library('elgg:blog');

$page_type = 'add';
$guid = elgg_extract('guid', $vars);

$params = blog_get_page_content_edit('add', $guid);

$sidebar = elgg_extract('sidebar', $params, '');
$sidebar .= elgg_view('blog/sidebar', ['page' => $page_type]);
$params['sidebar'] = $sidebar;

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
