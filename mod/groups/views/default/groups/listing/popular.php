<?php
/**
 * Renders a list of groups with most members
 *
 * Note: this view has a corresponding view in the rss view type, changes should be reflected
 *
 * @uses $vars['options'] Additional listing options
 */

elgg_deprecated_notice('The "groups/listing/popular" view has been deprecated. Popular sorting is supported in the "groups/listing/all" view.', '6.1');

$options = (array) elgg_extract('options', $vars);

$popular_options = [
	'relationship' => 'member',
	'inverse_relationship' => false,
];

$vars['options'] = array_merge($options, $popular_options);
$vars['getter'] = 'elgg_get_entities_from_relationship_count';

echo elgg_view('groups/listing/all', $vars);
