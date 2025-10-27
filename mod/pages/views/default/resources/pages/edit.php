<?php

$guid = (int) elgg_extract('guid', $vars);

/** @var \ElggPage $entity */
$entity = elgg_entity_gatekeeper($guid, 'object', 'page', true);

elgg_push_collection_breadcrumbs('object', 'page', $entity->getContainerEntity());

pages_prepare_parent_breadcrumbs($entity);

echo elgg_view_page(elgg_echo('edit:object:page'), [
	'content' => elgg_view_form('pages/edit', ['sticky_enabled' => true], [
		'entity' => $entity,
		'parent_guid' => $entity->getParentGUID(),
	]),
	'filter_id' => 'pages/edit',
]);
