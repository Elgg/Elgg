<?php
/**
 * Display bookmarks listing
 */

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'bookmarks',
	'no_results' => elgg_echo('bookmarks:none'),
	'distinct' => false,
]);
