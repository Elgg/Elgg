<?php
/**
 * Elgg Reported content admin page
 *
 * @package ElggReportedContent
 */

$list = elgg_list_entities_from_metadata([
	'type' => 'object',
	'subtype' => 'reported_content',
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