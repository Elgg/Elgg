<?php
/**
 * Display bookmarks listing
 *
 * Note: this view has a corresponding view in the default view type, changes should be reflected
 *
 * @uses $vars['options'] Additional listing options
 */

$defaults = [
	'type' => 'object',
	'subtype' => 'bookmarks',
	'full_view' => false,
	'distinct' => false,
	'pagination' => false,
];

$options = (array) elgg_extract('options', $vars, []);
$options = array_merge($defaults, $options);

echo elgg_list_entities($options);
