<?php
/**
 * List the latest reports
 */

/* @var $widget \ElggWidget */
$widget = elgg_extract('entity', $vars);

elgg_require_js('elgg/reportedcontent');

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'reported_content',
	'limit' => (int) $widget->num_display ?: 4,
	'pagination' => false,
	'order_by_metadata' => [
		'name' => 'state',
		'direction' => 'ASC',
		'as' => 'text',
	],
	'no_results' => elgg_echo('reportedcontent:none'),
]);
