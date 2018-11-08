<?php
/**
 * Display pages
 */

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'page',
	'metadata_name_value_pairs' => [
		'parent_guid' => 0,
	],
	'no_results' => elgg_echo('pages:none'),
]);
