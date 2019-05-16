<?php
/**
 * Display group's bookmarks
 *
 * Note: this view has a corresponding view in the rss view type, changes should be reflected
 *
 * @uses $vars['entity']
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'bookmarks',
	'container_guids' => $entity->guid,
	'no_results' => elgg_echo('bookmarks:none'),
]);
