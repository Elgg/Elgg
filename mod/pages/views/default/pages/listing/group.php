<?php
/**
 * Display group pages
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
	'container_guid' => $entity->guid,
	'no_results' => elgg_echo('pages:none'),
]);
