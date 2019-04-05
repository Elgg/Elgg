<?php
/**
 * List all files of a users friends
 *
 * Note: this view has a corresponding view in the default view type, changes should be reflected
 *
 * @uses $vars['entity'] the user to list for
 */

$entity = elgg_extract('entity', $vars);

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'file',
	'relationship' => 'friend',
	'relationship_guid' => $entity->guid,
	'relationship_join_on' => 'owner_guid',
	'pagination' => false,
]);
