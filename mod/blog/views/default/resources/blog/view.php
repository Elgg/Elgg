<?php

$page_type = elgg_extract('page_type', $vars);
$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'blog');

$blog = get_entity($guid);

elgg_push_entity_breadcrumbs($blog, false);

// no header or tabs for viewing an individual blog
$params = [
	'filter' => '',
	'title' => $blog->getDisplayName()
];

$params['content'] = elgg_view_entity($blog);

$sidebar = elgg_extract('sidebar', $params, '');
$sidebar .= elgg_view('blog/sidebar', ['page' => $page_type]);
$params['sidebar'] = $sidebar;

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
