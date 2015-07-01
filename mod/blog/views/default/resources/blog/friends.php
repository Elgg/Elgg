<?php

$page_type = $vars['page_type'];
$username = get_input('username');
$user = get_user_by_username($username);
if (!$user) {
	forward('', '404');
}
$params = blog_get_page_content_friends($user->guid);

if (isset($params['sidebar'])) {
	$params['sidebar'] .= elgg_view('blog/sidebar', array('page' => $page_type));
} else {
	$params['sidebar'] = elgg_view('blog/sidebar', array('page' => $page_type));
}

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
