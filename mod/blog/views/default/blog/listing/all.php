<?php
/**
 * List blogs
 *
 * Note: this view has a corresponding view in the default rss type, changes should be reflected
 *
 * @uses $vars['options'] Options
 * @uses $vars['created_after']  Only show blogs created after a date
 * @uses $vars['created_before'] Only show blogs created before a date
 * @uses $vars['status'] Filter by status
 */

$defaults = [
	'type' => 'object',
	'subtype' => 'blog',
	'full_view' => false,
	'no_results' => elgg_echo('blog:none'),
	'distinct' => false,
];

$options = (array) elgg_extract('options', $vars, []);
$options = array_merge($defaults, $options);

$after = elgg_extract('created_after', $vars);
if (!empty($after)) {
	$options['created_after'] = $after;
}

$before = elgg_extract('created_before', $vars);
if (!empty($before)) {
	$options['created_before'] = $before;
}

$status = elgg_extract('status', $vars);
if (!empty($status)) {
	$options['metadata_name_value_pairs']['status'] = $status;
}

echo elgg_list_entities($options);
