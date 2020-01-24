<?php
/**
 * Upload a new file
 */

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid);

$entity = get_entity($guid);

if (!$entity->canWriteToContainer(0, 'object', 'file')) {
	throw new \Elgg\EntityPermissionsException();
}

elgg_push_collection_breadcrumbs('object', 'file', $entity);

echo elgg_view_page(elgg_echo('add:object:file'), [
	'content' => elgg_view_form('file/upload', ['prevent_double_submit' => true], file_prepare_form_vars()),
]);
