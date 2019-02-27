<?php
/**
 * Edit a file
 *
 * @package ElggFile
 */

$file_guid = (int) elgg_extract('guid', $vars);

$file = get_entity($file_guid);

if (!$file instanceof ElggFile) {
	throw new \Elgg\EntityNotFoundException();
}

if (!$file->canEdit()) {
	throw new \Elgg\EntityPermissionsException();
}

$title = elgg_echo('edit:object:file');

elgg_push_entity_breadcrumbs($file);
elgg_push_breadcrumb($title);

$form_vars = ['enctype' => 'multipart/form-data'];
$body_vars = file_prepare_form_vars($file);

$content = elgg_view_form('file/upload', $form_vars, $body_vars);

$body = elgg_view_layout('content', [
	'content' => $content,
	'title' => $title,
	'filter' => '',
]);

echo elgg_view_page($title, $body);
