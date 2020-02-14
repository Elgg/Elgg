<?php

use Elgg\Exceptions\Http\EntityNotFoundException;

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', 'blog', true);

$blog = get_entity($guid);

$vars['entity'] = $blog;

elgg_push_entity_breadcrumbs($blog);

$revision = elgg_extract('revision', $vars);

$title = elgg_echo('edit:object:blog');

if ($revision) {
	$revision = elgg_get_annotation_from_id((int) $revision);
	$vars['revision'] = $revision;
	$title .= ' ' . elgg_echo('blog:edit_revision_notice');

	if (!$revision || !($revision->entity_guid == $guid)) {
		throw new EntityNotFoundException(elgg_echo('blog:error:revision_not_found'));
	}
}

$body_vars = blog_prepare_form_vars($blog, $revision);

$form_vars = $vars;
$form_vars['prevent_double_submit'] = false; // action is using the submit buttons to determine type of submission, disabled buttons are not submitted

$content = elgg_view_form('blog/save', $form_vars, $body_vars);

$sidebar = elgg_view('blog/sidebar/revisions', $vars);

echo elgg_view_page($title, [
	'content' => $content,
	'sidebar' => $sidebar,
	'filter_id' => 'blog/edit',
]);
