<?php

use Elgg\Exceptions\Http\EntityPermissionsException;

$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid);

$container = get_entity($guid);

// Make sure user has permissions to add a topic to container
if (!$container->canWriteToContainer(0, 'object', 'discussion')) {
	throw new EntityPermissionsException();
}

elgg_push_collection_breadcrumbs('object', 'discussion', $container);

$body_vars = discussion_prepare_form_vars();

echo elgg_view_page(elgg_echo('add:object:discussion'), [
	'content' => elgg_view_form('discussion/save', [], $body_vars),
	'filter_id' => 'discussion/edit',
]);
