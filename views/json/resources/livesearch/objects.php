<?php

elgg_gatekeeper();

$limit = get_input('limit', elgg_get_config('default_limit'));
$query = get_input('term', get_input('q'));
$input_name = get_input('name');

$options = [
	'query' => $query,
	'type' => 'object',
	'subtype' => get_input('subtype'),
	'limit' => $limit,
	'sort' => 'title',
	'order' => 'ASC',
	'fields' => ['metadata' => ['title']],
	'item_view' => 'search/entity',
	'input_name' => $input_name,
];

$body = elgg_list_entities($options, 'elgg_search');

echo elgg_view_page('', $body);
