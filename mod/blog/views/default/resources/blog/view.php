<?php

$page_type = elgg_extract('page_type', $vars);
$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'blog');
elgg_group_gatekeeper();

$blog = get_entity($guid);

elgg_set_page_owner_guid($blog->container_guid);

// no header or tabs for viewing an individual blog
$params = [
	'filter' => '',
	'title' => $blog->title
];

$container = $blog->getContainerEntity();
$crumbs_title = $container->name;

if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb($crumbs_title, "blog/group/$container->guid/all");
} else {
	elgg_push_breadcrumb($crumbs_title, "blog/owner/$container->username");
}

elgg_push_breadcrumb($blog->title);

$params['content'] = elgg_view_entity($blog, array('full_view' => true));

// check to see if we should allow comments
if ($blog->comments_on != 'Off' && $blog->status == 'published') {
	$params['content'] .= elgg_view_comments($blog);
}

$params['sidebar'] = elgg_view('blog/sidebar', array('page' => $page_type));

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
