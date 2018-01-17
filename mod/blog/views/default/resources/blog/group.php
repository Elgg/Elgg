<?php

$subpage = elgg_extract('subpage', $vars);
$page_type = 'group';
$group_guid = elgg_extract('guid', $vars, elgg_extract('group_guid', $vars)); // group_guid for BC
$lower = elgg_extract('lower', $vars);
$upper = elgg_extract('upper', $vars);

$group = get_entity($group_guid);

if (!$group instanceof ElggGroup) {
	throw new \Elgg\EntityNotFoundException();
}

if (!isset($subpage) || $subpage == 'all') {
	$params = blog_get_page_content_list($group_guid);
} else {
	$params = blog_get_page_content_archive($group_guid, $lower, $upper);
}

$sidebar = elgg_extract('sidebar', $params, '');
$sidebar .= elgg_view('blog/sidebar', ['page' => $page_type]);
$params['sidebar'] = $sidebar;

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
