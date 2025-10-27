<?php

$guid = (int) elgg_extract('guid', $vars);
$entity = elgg_entity_gatekeeper($guid, 'object', 'thewire');

elgg_push_entity_breadcrumbs($entity);

echo elgg_view_page(elgg_echo('thewire:by', [$entity->getOwnerEntity()->getDisplayName()]), [
	'content' => elgg_view_entity($entity),
	'entity' => $entity,
	'filter_id' => 'thewire/view',
]);
