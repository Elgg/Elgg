<?php
/**
 * Display friends pages
 *
 * @uses $vars['entity']
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggUser) {
	return;
}

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'page',
	'metadata_name_value_pairs' => [
		'parent_guid' => 0,
	],
	'relationship' => 'friend',
	'relationship_guid' => $entity->guid,
	'relationship_join_on' => 'container_guid',
	'no_results' => elgg_echo('pages:none'),
]);
