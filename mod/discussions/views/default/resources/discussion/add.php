<?php

use Elgg\Exceptions\Http\EntityPermissionsException;

$guid = (int) elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid);

$container = get_entity($guid);

// Make sure user has permissions to add a topic to container
if (!$container->canWriteToContainer(0, 'object', 'discussion')) {
	throw new EntityPermissionsException();
}

elgg_push_collection_breadcrumbs('object', 'discussion', $container);

echo elgg_view_page(elgg_echo('add:object:discussion'), [
	'content' => elgg_view_form('discussion/save', ['sticky_enabled' => true]),
	'filter_id' => 'discussion/edit',
]);
