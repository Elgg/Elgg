<?php
/**
 * List the latest reports
 */

/* @var $widget \ElggWidget */
$widget = elgg_extract('entity', $vars);

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'reported_content',
	'limit' => (int) $widget->num_display ?: 4,
	'pagination' => false,
	'metadata_name_value_pairs' => [
		'state' => 'active',
	],
	'no_results' => elgg_echo('reportedcontent:none'),
	'widget_more' => elgg_view_url('admin/administer_utilities/reportedcontent', elgg_echo('more')),
]);
