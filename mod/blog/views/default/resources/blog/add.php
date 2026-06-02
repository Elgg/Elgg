<?php

use Elgg\Exceptions\Http\EntityPermissionsException;

elgg_deprecated_notice("The resource view 'blog/add' has been deprecated", '7.1');

$container = elgg_get_page_owner_entity();
if (!$container->canWriteToContainer(0, 'object', 'blog')) {
	throw new EntityPermissionsException();
}

elgg_push_collection_breadcrumbs('object', 'blog', $container);

echo elgg_view_page(elgg_echo('add:object:blog'), [
	'content' => elgg_view_form('blog/edit', [
		'sticky_enabled' => true,
	]),
	'filter_id' => 'blog/edit',
]);
