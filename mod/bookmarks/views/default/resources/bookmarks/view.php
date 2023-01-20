<?php
/**
 * View a bookmark
 */

$guid = (int) elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'bookmarks');

$entity = get_entity($guid);

elgg_push_entity_breadcrumbs($entity, false);

echo elgg_view_page($entity->getDisplayName(), [
	'content' => elgg_view_entity($entity, [
		'full_view' => true,
		'show_responses' => true,
	]),
	'entity' => $entity,
	'sidebar' => elgg_view('object/bookmarks/elements/sidebar', [
		'entity' => $entity,
	]),
	'filter_id' => 'bookmarks/view',
]);
