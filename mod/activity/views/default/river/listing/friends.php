<?php
/**
 * List friends river activity
 *
 * @uses $vars['options'] Additional listing options
 * @uses $vars['entity']  The user to show friends activity for
 */

$options = (array) elgg_extract('options', $vars);
$entity = elgg_extract('entity', $vars);

$friends_options = [
	'relationship' => 'friend',
	'relationship_guid' => $entity->guid,
];

$vars['options'] = array_merge($options, $friends_options);

echo elgg_view('river/listing/all', $vars);
