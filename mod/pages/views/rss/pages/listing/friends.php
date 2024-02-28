<?php
/**
 * Display friends pages
 *
 * Note: this view has a corresponding view in the default view type, changes should be reflected
 *
 * @uses $vars['options'] Additional listing options
 * @uses $vars['entity'] the user to list friends content for
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

echo elgg_view('pages/listing/all', $vars);
