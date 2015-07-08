<?php

$subpage = elgg_extract('subpage', $vars);
$page_type = elgg_extract('page_type', $vars);
$group_guid = elgg_extract('group_guid', $vars);
$lower = elgg_extract('lower', $vars);
$upper = elgg_extract('upper', $vars);

$group = get_entity($group_guid);

if (!elgg_instanceof($group, 'group')) {
	forward('', '404');
}

if (!isset($subpage) || $subpage == 'all') {
	$params = blog_get_page_content_list($group_guid);
} else {
	$params = blog_get_page_content_archive($group_guid, $lower, $upper);
}

$params['sidebar'] = elgg_view('blog/sidebar', ['page' => $page_type]);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);