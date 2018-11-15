<?php
/**
 * Display group's bookmarks
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
