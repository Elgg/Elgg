<?php

$guid = (int) elgg_extract('guid', $vars);
elgg_entity_gatekeeper($guid, 'object', 'blog');

$entity = get_entity($guid);

elgg_push_entity_breadcrumbs($entity, false);

echo elgg_view_page($entity->getDisplayName(), [
	'content' => elgg_view_entity($entity, [
		'full_view' => true,
		'show_responses' => true,
	]),
	'filter_id' => 'blog/view',
	'entity' => $entity,
	'sidebar' => elgg_view('object/blog/elements/sidebar', [
		'entity' => $entity,
	]),
], 'default', [
	'entity' => $entity,
]);
