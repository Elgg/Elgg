<?php

elgg_deprecated_notice("The resource view 'blog/view' has been deprecated", '7.1');

$guid = (int) elgg_extract('guid', $vars);
$entity = elgg_entity_gatekeeper($guid, 'object', 'blog');

elgg_push_entity_breadcrumbs($entity);

echo elgg_view_page($entity->getDisplayName(), [
	'content' => elgg_view_entity($entity),
	'filter_id' => 'blog/view',
	'entity' => $entity,
	'sidebar' => elgg_view('object/blog/elements/sidebar', [
		'entity' => $entity,
	]),
]);
