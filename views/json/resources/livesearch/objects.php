<?php

elgg_gatekeeper();

$subtype = elgg_extract('subtype', $vars);
if (empty($subtype)) {
	$searchable = elgg_entity_types_with_capability('searchable');
	$subtype = elgg_extract('object', $searchable);
}

$options = [
	'query' => elgg_extract('term', $vars),
	'type' => 'object',
	'subtype' => $subtype,
	'limit' => elgg_extract('limit', $vars),
	'sort_by' => [
		'property_type' => 'metadata',
		'property' => 'title',
		'direction' => 'ASC',
	],
	'fields' => ['metadata' => ['title']],
	'item_view' => elgg_extract('item_view', $vars, 'search/entity'),
	'input_name' => elgg_extract('name', $vars),
];

$body = elgg_list_entities($options, 'elgg_search');

echo elgg_view_page('', $body);
