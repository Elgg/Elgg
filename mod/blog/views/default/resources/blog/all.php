<?php

$page_type = 'all';

elgg_load_library('elgg:blog');

// push all blogs breadcrumb
elgg_push_breadcrumb(elgg_echo('blog:blogs'), elgg_generate_url('collection:object:blog:all'));

$params = blog_get_page_content_list();

$params['sidebar'] = elgg_view('blog/sidebar', ['page' => $page_type]);

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
