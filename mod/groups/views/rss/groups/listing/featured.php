<?php
/**
 * Renders a list of featured groups
 *
 * Note: this view has a corresponding view in the default view type, changes should be reflected
 *
 * @uses $vars['options'] Additional listing options
 */

$options = (array) elgg_extract('options', $vars);

$featured_options = [
	'metadata_name_value_pairs' => [
		'name' => 'featured_group',
		'value' => 'yes',
	],
];

$vars['options'] = array_merge($options, $featured_options);

echo elgg_view('groups/listing/all', $vars);
