<?php

use Elgg\Exceptions\Http\EntityNotFoundException;

$guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', 'blog', true);

/* @var $blog \ElggBlog */
$blog = get_entity($guid);

$vars['entity'] = $blog;

elgg_push_entity_breadcrumbs($blog);

$revision_id = (int) elgg_extract('revision', $vars);
$revision = null;

$title = elgg_echo('edit:object:blog');

if (!empty($revision_id)) {
	$revision = elgg_get_annotation_from_id($revision_id);
	$vars['revision'] = $revision;
	$title .= ' ' . elgg_echo('blog:edit_revision_notice');

	if (!$revision instanceof \ElggAnnotation || $revision->entity_guid !== $guid) {
		throw new EntityNotFoundException(elgg_echo('blog:error:revision_not_found'));
	}
}

$form_vars = [
	'prevent_double_submit' => false, // action is using the submit buttons to determine type of submission, disabled buttons are not submitted
	'sticky_enabled' => true,
];

$body_vars = [
	'entity' => $blog,
	'revision' => $revision,
];

echo elgg_view_page($title, [
	'content' => elgg_view_form('blog/save', $form_vars, $body_vars),
	'sidebar' => elgg_view('blog/sidebar/revisions', $vars),
	'filter_id' => 'blog/edit',
]);
