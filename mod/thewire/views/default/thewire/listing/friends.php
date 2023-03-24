<?php
/**
 * Show Wire posts of the friends of the given entity
 *
 * @uses $vars['entity'] the entity to show friends posts for
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

$vars['options'] = [
	'relationship' => 'friend',
	'relationship_guid' => $entity->guid,
	'relationship_join_on' => 'container_guid',
];

echo elgg_view('thewire/listing/all', $vars);
