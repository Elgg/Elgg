<?php
/**
 * Display user's bookmarks
 *
 * @uses $vars['entity']
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggUser) {
	return;
}

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'bookmarks',
	'full_view' => false,
	'owner_guids' => $entity->guid,
	'no_results' => elgg_echo('bookmarks:none'),
	'preload_owners' => true,
	'preload_containers' => true,
]);