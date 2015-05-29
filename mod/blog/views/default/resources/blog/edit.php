<?php

elgg_gatekeeper();
$guid = get_input('guid');
$revision = get_input('revision');
$params = blog_get_page_content_edit('edit', $guid, $revision);
if (isset($params['sidebar'])) {
	$params['sidebar'] .= elgg_view('blog/sidebar', array('page' => $page_type));
} else {
	$params['sidebar'] = elgg_view('blog/sidebar', array('page' => $page_type));
}

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
