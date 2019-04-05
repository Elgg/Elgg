<?php
/**
 * List all files of a users friends
 *
 * Note: this view has a corresponding view in the rss view type, changes should be reflected
 *
 * @uses $vars['entity'] the user to list for
 */

$entity = elgg_extract('entity', $vars);

file_register_toggle();

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'file',
	'relationship' => 'friend',
	'relationship_guid' => $entity->guid,
	'relationship_join_on' => 'owner_guid',
	'no_results' => elgg_echo("file:none"),
]);
