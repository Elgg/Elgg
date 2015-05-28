<?php

elgg_gatekeeper();

$guid = get_input('guid');
$topic = get_entity($guid);
if (!elgg_instanceof($topic, 'object', 'groupforumtopic') || !$topic->canEdit()) {
	register_error(elgg_echo('discussion:topic:notfound'));
	forward();
}
$group = $topic->getContainerEntity();
if (!elgg_instanceof($group, 'group')) {
	register_error(elgg_echo('group:notfound'));
	forward();
}

$title = elgg_echo('groups:edittopic');

elgg_push_breadcrumb($group->name, "discussion/owner/$group->guid");
elgg_push_breadcrumb($topic->title, $topic->getURL());
elgg_push_breadcrumb($title);

$body_vars = discussion_prepare_form_vars($topic);
$content = elgg_view_form('discussion/save', array(), $body_vars);

$params = array(
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('discussion/sidebar/edit'),
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);