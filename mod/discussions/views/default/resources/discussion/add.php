<?php

use Elgg\Exceptions\Http\EntityPermissionsException;

elgg_deprecated_notice("The resource view 'discussion/add' has been deprecated", '7.1');

$container = elgg_get_page_owner_entity();
if (!$container->canWriteToContainer(0, 'object', 'discussion')) {
	throw new EntityPermissionsException();
}

elgg_push_collection_breadcrumbs('object', 'discussion', $container);

echo elgg_view_page(elgg_echo('add:object:discussion'), [
	'content' => elgg_view_form('discussion/edit', ['sticky_enabled' => true]),
	'filter_id' => 'discussion/edit',
]);
