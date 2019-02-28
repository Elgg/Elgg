<?php

elgg_gatekeeper();

$limit = (int) elgg_extract('limit', $vars, elgg_get_config('default_limit'));
$query = elgg_extract('term', $vars, elgg_extract('q', $vars));
$input_name = elgg_extract('name', $vars);

$options = [
	'query' => $query,
	'type' => 'object',
	'subtype' => elgg_extract('subtype', $vars),
	'limit' => $limit,
	'sort' => 'title',
	'order' => 'ASC',
	'fields' => ['metadata' => ['title']],
	'item_view' => 'search/entity',
	'input_name' => $input_name,
];

$body = elgg_list_entities($options, 'elgg_search');

echo elgg_view_page('', $body);
