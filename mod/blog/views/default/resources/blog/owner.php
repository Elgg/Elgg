<?php

$page_type = elgg_extract('page_type', $vars);
$username = elgg_extract('username', $vars);

$user = get_user_by_username($username);
if (!$user) {
	forward('', '404');
}
$params = blog_get_page_content_list($user->guid);

$params['sidebar'] = elgg_view('blog/sidebar', ['page' => $page_type]);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
