<?php
/**
 * Display pages
 *
 * Note: this view has a corresponding view in the default view type, changes should be reflected
 */

echo elgg_list_entities([
	'type' => 'object',
	'subtype' => 'page',
	'metadata_name_value_pairs' => [
		'parent_guid' => 0,
	],
	'pagination' => false,
]);
