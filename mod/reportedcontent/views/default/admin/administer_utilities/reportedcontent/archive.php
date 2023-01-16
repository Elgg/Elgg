<?php
/**
 * Elgg Reported content admin page (archived reports)
 */

echo elgg_view('navigation/tabs', [
	'tabs' => [
		[
			'text' => elgg_echo('reportedcontent:new'),
			'href' => 'admin/administer_utilities/reportedcontent',
		],
		[
			'text' => elgg_echo('reportedcontent:archived_reports'),
			'href' => 'admin/administer_utilities/reportedcontent/archive',
		],
	],
]);

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'reported_content',
	'metadata_name_value_pairs' => [
		'state' => 'archived',
	],
	'no_results' => elgg_echo('reportedcontent:none'),
]);
