<?php
/**
 * List all user files
 *
 * Note: this view has a corresponding view in the rss view type, changes should be reflected
 *
 * @uses $vars['entity'] the user to list for
 */

$owner = elgg_extract('entity', $vars);

file_register_toggle();

// List files
echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'file',
	'owner_guid' => $owner->guid,
	'no_results' => elgg_echo('file:none'),
	'distinct' => false,
]);
