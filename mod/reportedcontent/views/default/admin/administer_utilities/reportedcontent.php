<?php
/**
 * Elgg Reported content admin page
 */

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'reported_content',
	'order_by_metadata' => [
		'name' => 'state',
		'direction' => 'ASC',
		'as' => 'text',
	],
	'no_results' => elgg_echo('reportedcontent:none'),
]);
