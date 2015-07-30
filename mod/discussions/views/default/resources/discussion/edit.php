<?php

elgg_gatekeeper();

$guid = elgg_extract('guid', $vars);
$topic = get_entity($guid);
if (!elgg_instanceof($topic, 'object', 'discussion') || !$topic->canEdit()) {
	register_error(elgg_echo('discussion:topic:notfound'));
	forward();
}
$container = $topic->getContainerEntity();

$title = elgg_echo('discussion:topic:edit');

elgg_push_breadcrumb($container->getDisplayName(), "discussion/owner/$container->guid");
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