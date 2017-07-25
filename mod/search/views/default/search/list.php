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

if (!is_array($entities) || !count($entities)) {
	return false;
}

$query = http_build_query(
	[
		'q' => $vars['params']['query'],
		'entity_type' => $vars['params']['type'],
		'entity_subtype' => $vars['params']['subtype'],
		'limit' => $vars['params']['limit'],
		'offset' => $vars['params']['offset'],
		'search_type' => $vars['params']['search_type'],
		'container_guid' => $vars['params']['container_guid'],
	//@todo include vars for sorting, order, and friend-only.
	]
);

$url = elgg_get_site_url() . "search?$query";

$more_items = $vars['results']['count'] - ($vars['params']['offset'] + $vars['params']['limit']);

// get pagination
if (array_key_exists('pagination', $vars['params']) && $vars['params']['pagination']) {
	$nav = elgg_view('navigation/pagination', [
		'base_url' => $url,
		'offset' => $vars['params']['offset'],
		'count' => $vars['results']['count'],
		'limit' => $vars['params']['limit'],
	]);
	$show_more = false;
} else {
	// faceted search page so no pagination
	$nav = '';
	$show_more = $more_items > 0;
}

// figure out what we're dealing with.
$type_str = null;

if (array_key_exists('type', $vars['params']) && array_key_exists('subtype', $vars['params'])) {
	$type_str_tmp = "item:{$vars['params']['type']}:{$vars['params']['subtype']}";
	if (elgg_language_key_exists($type_str_tmp)) {
		$type_str = elgg_echo($type_str_tmp);
	}
}

if (!$type_str && array_key_exists('type', $vars['params'])) {
	$type_str = elgg_echo("item:{$vars['params']['type']}");
}

if (!$type_str) {
	$type_str = elgg_echo('search:unknown_entity');
}

// allow overrides for titles
$search_type = elgg_extract('search_type', $vars['params']);
if ($search_type && elgg_language_key_exists("search_types:{$search_type}")) {
	$type_str = elgg_echo("search_types:{$search_type}");
}

if ($show_more) {
	$more_str = elgg_echo('search:more', [$count, $type_str]);
	$more_url = elgg_http_remove_url_query_element($url, 'limit');
	$more_link = "<li class='elgg-item'><a href=\"$more_url\">$more_str</a></li>";
} else {
	$more_link = '';
}

$body = elgg_view_title($type_str, [
	'class' => 'search-heading-category',
]);

$list_body = '';
$view_params = $vars['params'];
foreach ($entities as $entity) {
	$view_params['type'] = $entity->getType();
	$view_params['subtype'] = $entity->getSubtype();
	
	$view = search_get_search_view($view_params, 'entity');
	if (empty($view)) {
		continue;
	}
	
	$id = "elgg-{$entity->getType()}-{$entity->getGUID()}";
	$list_body .= "<li id=\"$id\" class=\"elgg-item\">";
	$list_body .= elgg_view($view, [
		'entity' => $entity,
		'params' => $view_params,
		'results' => $vars['results']
	]);
	$list_body .= '</li>';
}

if (!empty($list_body)) {
	$body .= '<ul class="elgg-list search-list">';
	$body .= $list_body;
	$body .= $more_link;
	$body .= '</ul>';
}

echo $body;
echo $nav;
