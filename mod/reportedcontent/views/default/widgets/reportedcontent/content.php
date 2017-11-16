<?php
/**
 * List the latest reports
 */

$widget = elgg_extract('entity', $vars);

$num_display = (int) $widget->num_display ?: 4;

$list = elgg_list_entities([
	'type' => 'object',
	'subtype' => 'reported_content',
	'limit' => $num_display,
	'pagination' => false,
	'order_by_metadata' => [
		'name' => 'state',
		'direction' => 'ASC',
		'as' => 'text',
	],
]);
if (!$list) {
	$list = '<p class="mtm">' . elgg_echo('reportedcontent:none') . '</p>';
}

echo $list;

// elgg_require_js() fails when widget is newly added
?><script>require(['elgg/reportedcontent']);</script>
