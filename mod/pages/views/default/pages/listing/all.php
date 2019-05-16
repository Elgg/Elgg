<?php
/**
 * Display pages
 *
 * Note: this view has a corresponding view in the rss view type, changes should be reflected
 */

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'page',
	'metadata_name_value_pairs' => [
		'parent_guid' => 0,
	],
	'no_results' => elgg_echo('pages:none'),
]);
