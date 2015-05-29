<?php

$page_type = get_input('page_type');
$group_guid = get_input('group_guid');
$group = get_entity($group_guid);
if (!elgg_instanceof($group, 'group')) {
	forward('', '404');
}
if (!isset($page_type) || $page_type == 'all') {
	$params = blog_get_page_content_list($group_guid);
} else {
	$params = blog_get_page_content_archive($group_guid, $lower, $upper);
}
if (isset($params['sidebar'])) {
	$params['sidebar'] .= elgg_view('blog/sidebar', array('page' => $page_type));
} else {
	$params['sidebar'] = elgg_view('blog/sidebar', array('page' => $page_type));
}

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);