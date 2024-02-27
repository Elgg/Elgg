<?php
/**
 * List all files of a users friends
 *
 * Note: this view has a corresponding view in the rss view type, changes should be reflected
 *
 * @uses $vars['options'] Additional listing options
 * @uses $vars['entity']  User to list friends content for
 */

$options = (array) elgg_extract('options', $vars);
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggUser) {
	return;
}

$friends_options = [
	'relationship' => 'friend',
	'relationship_guid' => $entity->guid,
	'relationship_join_on' => 'owner_guid',
];

$vars['options'] = array_merge($options, $friends_options);

echo elgg_view('file/listing/all', $vars);
