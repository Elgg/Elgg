<?php

$guid = elgg_extract('guid', $vars);
$group = get_entity($guid);
if (!($group instanceof ElggGroup) || !$group->canEdit()) {
	register_error(elgg_echo('groups:noaccess'));
	forward(REFERER);
}

elgg_set_page_owner_guid($guid);

elgg_push_breadcrumb(elgg_echo('groups'), "groups/all");
elgg_push_breadcrumb($group->getDisplayName(), $group->getURL());

$title = elgg_echo('groups:invite:title');

$content = elgg_view_form('groups/invite', [
	'id' => 'invite_to_group',
	'class' => 'elgg-form-alt mtm',
], [
	'entity' => $group,
]);

$body = elgg_view_layout('content', [
	'content' => $content,
	'title' => $title,
	'filter' => '',
]);

echo elgg_view_page($title, $body);
