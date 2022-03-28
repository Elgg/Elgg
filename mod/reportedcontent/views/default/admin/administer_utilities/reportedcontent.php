<?php
/**
 * Elgg Reported content admin page (new reports)
 */

echo elgg_view('navigation/tabs', [
	'tabs' => [
		[
			'text' => elgg_echo('reportedcontent:new'),
			'href' => 'admin/administer_utilities/reportedcontent',
		],
		[
			'text' => elgg_echo('reportedcontent:archived'),
			'href' => 'admin/administer_utilities/reportedcontent/archive',
		],
	],
]);

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'reported_content',
	'metadata_name_value_pairs' => [
		[
			'name' => 'state',
			'value' => 'active',
		],
	],
	'no_results' => elgg_echo('reportedcontent:none'),
]);
