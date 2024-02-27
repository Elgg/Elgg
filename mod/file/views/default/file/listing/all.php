<?php
/**
 * List all files
 *
 * Note: this view has a corresponding view in the rss view type, changes should be reflected
 *
 * @uses $vars['options'] Additional listing options
 */

file_register_toggle();

$defaults = [
	'type' => 'object',
	'subtype' => 'file',
	'full_view' => false,
	'no_results' => elgg_echo('file:none'),
	'distinct' => false,
];

$options = (array) elgg_extract('options', $vars, []);
$options = array_merge($defaults, $options);

echo elgg_list_entities($options);
