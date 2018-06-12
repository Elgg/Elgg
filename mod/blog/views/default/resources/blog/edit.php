<?php

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', 'blog');

$blog = get_entity($guid);
if (!$blog->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

$vars['entity'] = $blog;

elgg_push_entity_breadcrumbs($blog);
elgg_push_breadcrumb(elgg_echo('edit'));

$revision = elgg_extract('revision', $vars);

$title = elgg_echo('edit:object:blog');

if ($revision) {
	$revision = elgg_get_annotation_from_id((int) $revision);
	$vars['revision'] = $revision;
	$title .= ' ' . elgg_echo('blog:edit_revision_notice');

	if (!$revision || !($revision->entity_guid == $guid)) {
		throw new \Elgg\EntityNotFoundException(elgg_echo('blog:error:revision_not_found'));
	}
}

$body_vars = blog_prepare_form_vars($blog, $revision);
$content = elgg_view_form('blog/save', $vars, $body_vars);

$sidebar = elgg_view('blog/sidebar/revisions', $vars);

$body = elgg_view_layout('default', [
	'title' => $title,
	'content' => $content,
	'sidebar' => $sidebar,
	'filter_id' => 'blog/edit',
]);

echo elgg_view_page($title, $body);
