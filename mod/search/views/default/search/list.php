<?php

/**
 * List a section of search results corresponding in a particular type/subtype
 * or search type (comments for example)
 *
 * @uses $vars['results'] Array of data related to search results including:
 *                          - 'entities' Array of entities to be displayed
 *                          - 'count'    Total number of results
 * @uses $params  Array of parameters including:
 *                          - 'type'        Entity type
 *                          - 'subtype'     Entity subtype
 *                          - 'search_type' Type of search: 'entities', 'comments', 'tags'
 *                          - 'offset'      Offset in search results
 *                          - 'limit'       Number of results per page
 *                          - 'pagination'  Display pagination?
 */

$results = elgg_extract('results', $vars);
$entities = elgg_extract('entities', $results, []);
$params = elgg_extract('params', $vars, []);
if (!is_array($entities) || empty($entities) || empty($params)) {
	return;
}

$count = elgg_extract('count', $results);

$offset = elgg_extract('offset', $params);
$limit = elgg_extract('limit', $params);

$keys = [
	"search_types:{$params['search_type']}",
	"item:{$params['type']}:{$params['subtype']}",
	"item:{$params['type']}",
];

$type_label = elgg_echo('search:unknown_entity');
foreach ($keys as $key) {
	if (elgg_language_key_exists($key)) {
		$type_label = elgg_echo($key);
		break;
	}
}

$base_url = elgg_generate_url('default:search', [
	'q' => $params['query'],
	'entity_type' => $params['type'],
	'entity_subtype' => $params['subtype'],
	'limit' => $limit,
	'offset' => $offset,
	'search_type' => $params['search_type'],
	'container_guid' => elgg_extract('container_guid', $params),
]);

$more_items = $count - ($offset + $limit);

$pagination = '';
if (array_key_exists('pagination', $params) && $params['pagination']) {
	$pagination = elgg_view('navigation/pagination', [
		'base_url' => $base_url,
		'offset' => $offset,
		'count' => $count,
		'limit' => $limit,
	]);
} else if ($more_items > 0) {
	$pagination = elgg_view('output/url', [
		'class' => 'elgg-widget-more',
		'href' => elgg_http_remove_url_query_element($base_url, 'limit'),
		'text' => elgg_echo('search:more', [$count - count($entities), $type_label]),
	]);
}

$list = elgg_view_entity_list($entities, [
	'params' => $params,
	'results' => $results,
	'item_view' => 'search/entity',
	'pagination' => false,
]);

if (empty($list)) {
	return;
}

echo elgg_view_module('info', $type_label, $list, [
	'footer' => $pagination,
	'class' => 'elgg-module-search-results',
]);
