<?php
/**
 * Upload a new file
 */

use Elgg\Exceptions\Http\EntityPermissionsException;

$guid = elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid);

$entity = get_entity($guid);

if (!$entity->canWriteToContainer(0, 'object', 'file')) {
	throw new EntityPermissionsException();
}

elgg_push_collection_breadcrumbs('object', 'file', $entity);

echo elgg_view_page(elgg_echo('add:object:file'), [
	'content' => elgg_view_form('file/upload', [], file_prepare_form_vars()),
	'filter_id' => 'file/edit',
]);
