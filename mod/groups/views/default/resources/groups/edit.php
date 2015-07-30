<?php

elgg_gatekeeper();

elgg_require_js('elgg/groups/edit');

$guid = elgg_extract('guid', $vars);
$title = elgg_echo("groups:edit");
$group = get_entity($guid);

if (elgg_instanceof($group, 'group') && $group->canEdit()) {
	elgg_set_page_owner_guid($group->getGUID());
	elgg_push_breadcrumb($group->name, $group->getURL());
	elgg_push_breadcrumb($title);
	$content = elgg_view("groups/edit", array('entity' => $group));
} else {
	$content = elgg_echo('groups:noaccess');
}

$params = array(
	'content' => $content,
	'title' => $title,
	'filter' => '',
);
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);