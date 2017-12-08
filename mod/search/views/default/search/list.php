<?php

/**
 * List a section of search results corresponding in a particular type/subtype
 * or search type (comments for example)
 *
 * @uses $vars['results'] Array of data related to search results including:
 *                          - 'entities' Array of entities to be displayed
 *                          - 'count'    Total number of results
 * @uses $vars['params']  Array of parameters including:
 *                          - 'type'        Entity type
 *                          - 'subtype'     Entity subtype
 *                          - 'search_type' Type of search: 'entities', 'comments', 'tags'
 *                          - 'offset'      Offset in search results
 *                          - 'limit'       Number of results per page
 *                          - 'pagination'  Display pagination?
 */
$entities = $vars['results']['entities'];
$count = $vars['results']['count'] - count($entities);

if (!is_array($entities) || empty($entities)) {
	return;
}

$keys = [
	"search_types:{$vars['params']['search_type']}",
	"item:{$vars['params']['type']}:{$vars['params']['subtype']}",
	"item:{$vars['params']['type']}",
];

$type_label = elgg_echo('search:unknown_entity');
foreach ($keys as $key) {
	if (elgg_language_key_exists($key)) {
		$type_label = elgg_echo($key);
		break;
	}
}

$base_url = elgg_http_add_url_query_elements('search', [
	'q' => $vars['params']['query'],
	'entity_type' => $vars['params']['type'],
	'entity_subtype' => $vars['params']['subtype'],
	'limit' => $vars['params']['limit'],
	'offset' => $vars['params']['offset'],
	'search_type' => $vars['params']['search_type'],
	'container_guid' => $vars['params']['container_guid'],
]);


$more_items = $vars['results']['count'] - ($vars['params']['offset'] + $vars['params']['limit']);

$pagination = '';
if (array_key_exists('pagination', $vars['params']) && $vars['params']['pagination']) {
	$pagination = elgg_view('navigation/pagination', [
		'base_url' => $base_url,
		'offset' => $vars['params']['offset'],
		'count' => $vars['results']['count'],
		'limit' => $vars['params']['limit'],
	]);
} else if ($more_items > 0) {
	$pagination = elgg_view('output/url', [
		'class' => 'elgg-widget-more',
		'href' => elgg_http_remove_url_query_element($base_url, 'limit'),
		'text' => elgg_echo('search:more', [$count, $type_label]),
	]);
}

$list = elgg_view_entity_list($entities, [
	'params' => $vars['params'],
	'results' => $vars['results'],
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
