<?php

$guid = elgg_extract('guid', $vars);
if (!$guid) {
	$guid = elgg_get_logged_in_user_guid();
}

elgg_entity_gatekeeper($guid);

$container = get_entity($guid);

if (!$container->canWriteToContainer(0, 'object', 'blog')) {
	throw new \Elgg\EntityPermissionsException();
}

elgg_push_collection_breadcrumbs('object', 'blog', $container);
elgg_push_breadcrumb(elgg_echo('add:object:blog'));

$title = elgg_echo('add:object:blog');
$content = elgg_view_form('blog/save', $vars, blog_prepare_form_vars());

$layout = elgg_view_layout('default', [
	'title' => $title,
	'content' => $content,
	'filter_id' => 'blog/edit',
]);

echo elgg_view_page($title, $layout);
