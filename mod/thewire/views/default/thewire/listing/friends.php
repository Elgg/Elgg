<?php
/**
 * Show Wire posts of the friends of the given entity
 *
 * @uses $vars['options'] Additional listing options
 * @uses $vars['entity'] the entity to show friends posts for
 */

$options = (array) elgg_extract('options', $vars);
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggUser) {
	return;
}

$friends_options = [
	'relationship' => 'friend',
	'relationship_guid' => $entity->guid,
	'relationship_join_on' => 'container_guid',
];

$vars['options'] = array_merge($options, $friends_options);

echo elgg_view('thewire/listing/all', $vars);
