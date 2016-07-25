<?php

elgg_gatekeeper();

$guid = elgg_extract('guid', $vars);
$reply = get_entity($guid);
if (!elgg_instanceof($reply, 'object', 'discussion_reply') || !$reply->canEdit()) {
	register_error(elgg_echo('discussion:reply:error:notfound'));
	forward();
}
$topic = $reply->getContainerEntity();
if (!elgg_instanceof($topic, 'object', 'discussion')) {
	register_error(elgg_echo('discussion:topic:notfound'));
	forward();
}
$group = $topic->getContainerEntity();
if (!elgg_instanceof($group, 'group')) {
	register_error(elgg_echo('group:notfound'));
	forward();
}

$title = elgg_echo('discussion:reply:edit');

elgg_push_breadcrumb($group->name, "discussion/owner/$group->guid");
elgg_push_breadcrumb($topic->title, $topic->getURL());
elgg_push_breadcrumb($title);

$params = array(
	'guid' => $reply->guid,
	'hidden' => false,
);

$content = elgg_view('ajax/discussion/reply/edit', $params);

$params = array(
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('discussion/sidebar/edit'),
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
