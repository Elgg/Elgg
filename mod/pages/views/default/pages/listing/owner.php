<?php
/**
 * Display user's pages
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'page',
	'metadata_name_value_pairs' => [
		'parent_guid' => 0,
	],
	'owner_guid' => $entity->guid,
	'full_view' => false,
	'no_results' => elgg_echo('pages:none'),
	'preload_owners' => true,
]);