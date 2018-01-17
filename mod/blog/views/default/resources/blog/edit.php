<?php

elgg_gatekeeper();

$page_type = 'edit';
$guid = elgg_extract('guid', $vars);
$revision = elgg_extract('revision', $vars);

$params = blog_get_page_content_edit('edit', $guid, $revision);

$sidebar = elgg_extract('sidebar', $params, '');
$sidebar .= elgg_view('blog/sidebar', ['page' => $page_type]);
$params['sidebar'] = $sidebar;

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
