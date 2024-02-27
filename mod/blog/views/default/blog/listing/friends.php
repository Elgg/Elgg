<?php
/**
 * List friends' blogs
 *
 * Note: this view has a corresponding view in the default rss type, changes should be reflected
 *
 * @uses $vars['options']        Additional listing options
 * @uses $vars['entity']         User
 * @uses $vars['created_after']  Only show blogs created after a date
 * @uses $vars['created_before'] Only show blogs created before a date
 * @uses $vars['status']         Filter by status
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

echo elgg_view('blog/listing/all', $vars);
