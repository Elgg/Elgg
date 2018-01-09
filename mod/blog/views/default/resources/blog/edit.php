<?php

elgg_gatekeeper();

elgg_load_library('elgg:blog');

// push all blogs breadcrumb
elgg_push_breadcrumb(elgg_echo('blog:blogs'), elgg_generate_url('collection:object:blog:all'));

$page_type = 'edit';
$guid = elgg_extract('guid', $vars);
$revision = elgg_extract('revision', $vars);

$params = blog_get_page_content_edit('edit', $guid, $revision);

if (isset($params['sidebar'])) {
	$params['sidebar'] .= elgg_view('blog/sidebar', ['page' => $page_type]);
} else {
	$params['sidebar'] = elgg_view('blog/sidebar', ['page' => $page_type]);
}

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
