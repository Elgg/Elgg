<?php
/**
 * Display bookmarks listing
 */

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'bookmarks',
	'full_view' => false,
	'no_results' => elgg_echo('bookmarks:none'),
	'preload_owners' => true,
	'preload_containers' => true,
	'distinct' => false,
]);