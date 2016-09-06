<?php

$guid = elgg_extract('guid', $vars);

elgg_register_rss_link();

elgg_entity_gatekeeper($guid, 'object', 'discussion');

$topic = get_entity($guid);

$container = $topic->getContainerEntity();

elgg_require_js('elgg/discussion');

elgg_set_page_owner_guid($container->getGUID());

elgg_group_gatekeeper();

if ($container instanceof ElggGroup) {
	$owner_url = "discussion/group/$container->guid";
} else {
	$owner_url = "discussion/owner/$container->guid";
}

elgg_push_breadcrumb($container->getDisplayName(), $owner_url);
elgg_push_breadcrumb($topic->title);

$params = array(
	'topic' => $topic,
	'show_add_form' => $topic->canWriteToContainer(0, 'object', 'discussion_reply'),
);

$content = elgg_view_entity($topic, array('full_view' => true));
$content .= elgg_view('discussion/replies', $params);
if ($topic->status == 'closed') {
	$content .= elgg_view('discussion/closed');
}

$params = array(
	'content' => $content,
	'title' => $topic->title,
	'sidebar' => elgg_view('discussion/sidebar'),
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($topic->title, $body);
