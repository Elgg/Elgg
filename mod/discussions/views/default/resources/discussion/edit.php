<?php

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'discussion');

$topic = get_entity($guid);

if (!$topic->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

$title = elgg_echo('edit:object:discussion');

elgg_push_entity_breadcrumbs($topic);
elgg_push_breadcrumb($title);

$body_vars = discussion_prepare_form_vars($topic);
$content = elgg_view_form('discussion/save', [], $body_vars);

$params = [
	'content' => $content,
	'title' => $title,
	'sidebar' => elgg_view('discussion/sidebar/edit'),
	'filter' => '',
];
$body = elgg_view_layout('content', $params);

echo elgg_view_page($title, $body);
