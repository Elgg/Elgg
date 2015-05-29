<?php

$guid = get_input('guid');
$params = blog_get_page_content_read($guid);

if (isset($params['sidebar'])) {
	$params['sidebar'] .= elgg_view('blog/sidebar', array('page' => $page_type));
} else {
	$params['sidebar'] = elgg_view('blog/sidebar', array('page' => $page_type));
}

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
