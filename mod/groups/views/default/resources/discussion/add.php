<?php

elgg_gatekeeper();

$guid = get_input('guid');
$group = get_entity($guid);
if (!elgg_instanceof($group, 'group')) {
	register_error(elgg_echo('group:notfound'));
	forward();
}

// make sure user has permissions to add a topic to container
if (!$group->canWriteToContainer(0, 'object', 'groupforumtopic')) {
	register_error(elgg_echo('groups:permissions:error'));
	forward($group->getURL());
}

$title = elgg_echo('groups:addtopic');

elgg_push_breadcrumb($group->name, "discussion/owner/$group->guid");
elgg_push_breadcrumb($title);

$body_vars = discussion_prepare_form_vars();
$content = elgg_view_form('discussion/save', array(), $body_vars);

$params = array(
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('discussion/sidebar/edit'),
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);