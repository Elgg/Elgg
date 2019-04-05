<?php
/**
 * Display user's pages
 *
 * Note: this view has a corresponding view in the rss view type, changes should be reflected
 *
 * @uses $vars['entity'] the user
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
	'no_results' => elgg_echo('pages:none'),
]);
