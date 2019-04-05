<?php
/**
 * Display bookmarks listing
 *
 * Note: this view has a corresponding view in the rss view type, changes should be reflected
 */

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'bookmarks',
	'no_results' => elgg_echo('bookmarks:none'),
	'distinct' => false,
]);
