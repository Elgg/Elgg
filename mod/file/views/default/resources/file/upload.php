<?php
/**
 * Upload a new file
 *
 * @package ElggFile
 */

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid);

$entity = get_entity($guid);

if (!$entity->canWriteToContainer(0, 'object', 'file')) {
	throw new \Elgg\EntityPermissionsException();
}

$title = elgg_echo('add:object:file');

elgg_push_collection_breadcrumbs('object', 'file', $entity);

// create form
$content = elgg_view_form('file/upload', [], file_prepare_form_vars());

$body = elgg_view_layout('default', [
	'content' => $content,
	'title' => $title,
]);

echo elgg_view_page($title, $body);
