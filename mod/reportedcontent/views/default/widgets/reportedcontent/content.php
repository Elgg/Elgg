<?php
/**
 * List the latest reports
 */

$widget = elgg_extract('entity', $vars);
if (!$widget instanceof \ElggWidget) {
	return;
}

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'reported_content',
	'limit' => (int) $widget->num_display ?: 4,
	'pagination' => false,
	'metadata_name_value_pairs' => [
		'state' => 'active',
	],
	'no_results' => elgg_echo('reportedcontent:none'),
	'widget_more' => elgg_view_url($widget->getURL(), elgg_echo('more')),
]);
