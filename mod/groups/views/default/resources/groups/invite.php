<?php

elgg_gatekeeper();

$guid = elgg_extract('guid', $vars);
elgg_set_page_owner_guid($guid);

$title = elgg_echo('groups:invite:title');

$group = get_entity($guid);
if (!elgg_instanceof($group, 'group') || !$group->canEdit()) {
	register_error(elgg_echo('groups:noaccess'));
	forward(REFERER);
}

$content = elgg_view_form('groups/invite', array(
	'id' => 'invite_to_group',
	'class' => 'elgg-form-alt mtm',
), array(
	'entity' => $group,
));

elgg_push_breadcrumb($group->name, $group->getURL());
elgg_push_breadcrumb(elgg_echo('groups:invite'));

$params = array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);