<?php

use Elgg\Exceptions\Http\EntityPermissionsException;

$container = elgg_get_page_owner_entity();
if (!$container->canWriteToContainer(0, 'group', 'group')) {
	throw new EntityPermissionsException();
}

elgg_push_collection_breadcrumbs('group', 'group');

echo elgg_view_page(elgg_echo('groups:add'), [
	'content' => elgg_view('groups/edit'),
	'filter_id' => 'groups/edit',
]);
