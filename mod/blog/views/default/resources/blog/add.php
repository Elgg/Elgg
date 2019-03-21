<?php

elgg_gatekeeper();

$page_type = elgg_extract('page_type', $vars);
$guid = (int) elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid);
elgg_group_gatekeeper(true, $guid);

$container = get_entity($guid);

// Make sure user has permissions to add to container
if (!$container->canWriteToContainer(0, 'object', 'blog')) {
	register_error(elgg_echo('actionunauthorized'));
	forward(REFERER);
}

$params = blog_get_page_content_edit('add', $guid);

if (isset($params['sidebar'])) {
	$params['sidebar'] .= elgg_view('blog/sidebar', ['page' => $page_type]);
} else {
	$params['sidebar'] = elgg_view('blog/sidebar', ['page' => $page_type]);
}

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
