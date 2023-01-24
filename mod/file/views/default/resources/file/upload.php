<?php
/**
 * Upload a new file
 */

use Elgg\Exceptions\Http\EntityPermissionsException;

$container = elgg_get_page_owner_entity();
if (!$container->canWriteToContainer(0, 'object', 'file')) {
	throw new EntityPermissionsException();
}

elgg_push_collection_breadcrumbs('object', 'file', $container);

echo elgg_view_page(elgg_echo('add:object:file'), [
	'content' => elgg_view_form('file/upload', ['sticky_enabled' => true]),
	'filter_id' => 'file/edit',
]);
