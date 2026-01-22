<?php

$guid = (int) elgg_extract('guid', $vars);
$entity = elgg_entity_gatekeeper($guid, 'object', 'bookmarks', true);

elgg_push_entity_breadcrumbs($entity);

echo elgg_view_page(elgg_echo('edit:object:bookmarks'), [
	'filter_id' => 'bookmarks/edit',
	'content' => elgg_view_form('bookmarks/edit', ['sticky_enabled' => true], ['entity' => $entity]),
]);
