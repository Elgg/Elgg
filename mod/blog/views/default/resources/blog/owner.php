<?php

$page_type = 'owner';
$username = elgg_extract('username', $vars);

$user = get_user_by_username($username);
if (!$user) {
	throw new \Elgg\EntityNotFoundException();
}
$params = blog_get_page_content_list($user->guid);

$sidebar = elgg_extract('sidebar', $params, '');
$sidebar .= elgg_view('blog/sidebar', ['page' => $page_type]);
$params['sidebar'] = $sidebar;

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
