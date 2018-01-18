<?php
/**
 * Display friends' bookmarks
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
	'relationship' => 'friend',
	'relationship_guid' => $entity->guid,
	'relationship_join_on' => 'owner_guid',
	'no_results' => elgg_echo('bookmarks:none'),
	'preload_owners' => true,
	'preload_containers' => true,
]);