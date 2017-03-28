<?php
/**
 * List the latest reports
 */

echo elgg_list_entities_from_metadata([
	'type' => 'object',
	'subtype' => 'reported_content',
	'limit' => $vars['entity']->num_display,
	'pagination' => false,
	'order_by_metadata' => [
		'name' => 'state',
		'direction' => 'ASC',
		'as' => 'text',
	],
	'list_class' => 'list-group-flush',
	'no_results' => elgg_echo('reportedcontent:none'),
]);

// elgg_require_js() fails when widget is newly added
?><script>require(['elgg/reportedcontent']);</script>
