<?php

$guid = (int) elgg_extract('guid', $vars);
$entity = elgg_entity_gatekeeper($guid, 'object', 'discussion', true);

elgg_push_entity_breadcrumbs($entity);

echo elgg_view_page(elgg_echo('edit:object:discussion'), [
	'content' => elgg_view_form('discussion/edit', ['sticky_enabled' => true], ['entity' => $entity]),
	'filter_id' => 'discussion/edit',
]);
