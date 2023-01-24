<?php

use Elgg\Exceptions\Http\EntityPermissionsException;

$container = elgg_get_page_owner_entity();
if (!$container->canWriteToContainer(0, 'object', 'discussion')) {
	throw new EntityPermissionsException();
}

elgg_push_collection_breadcrumbs('object', 'discussion', $container);

echo elgg_view_page(elgg_echo('add:object:discussion'), [
	'content' => elgg_view_form('discussion/save', ['sticky_enabled' => true]),
	'filter_id' => 'discussion/edit',
]);
