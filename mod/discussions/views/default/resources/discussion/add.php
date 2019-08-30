<?php

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid);

$container = get_entity($guid);

// Make sure user has permissions to add a topic to container
if (!$container->canWriteToContainer(0, 'object', 'discussion')) {
	throw new \Elgg\EntityPermissionsException();
}

$title = elgg_echo('add:object:discussion');

elgg_push_collection_breadcrumbs('object', 'discussion', $container);
elgg_push_breadcrumb($title);

$body_vars = discussion_prepare_form_vars();
$content = elgg_view_form('discussion/save', [], $body_vars);

$body = elgg_view_layout('default', [
	'title' => $title,
	'content' => $content,
]);

echo elgg_view_page($title, $body);
