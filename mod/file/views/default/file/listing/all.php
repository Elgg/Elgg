<?php
/**
 * List all files
 *
 * Note: this view has a corresponding view in the rss view type, changes should be reflected
 */

file_register_toggle();

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'file',
	'no_results' => elgg_echo("file:none"),
	'distinct' => false,
]);
