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
	'full_view' => false,
	'no_results' => elgg_echo('pages:none'),
	'preload_owners' => true,
	'preload_containers' => true,
]);