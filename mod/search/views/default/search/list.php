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
 *                          - 'offset'
 *                          - 'limit'
 *                          - 'pagination'  Display pagination?
 */

$entities = $vars['results']['entities'];
$count = $vars['results']['count'] - count($entities);

if (!is_array($entities) || !count($entities)) {
	return FALSE;
}

// @todo why are limit and offset pulled from input here and from $vars['params'] later
$query = http_build_query(
	array(
		'q' => $vars['params']['query'],
		'entity_type' => $vars['params']['type'],
		'entity_subtype' => $vars['params']['subtype'],
		'limit' => get_input('limit', 10),
		'offset' => get_input('offset', 0),
		'search_type' => $vars['params']['search_type'],
	//@todo include vars for sorting, order, and friend-only.
	)
);

$url = elgg_get_site_url() . "pg/search?$query";

// get pagination
if (array_key_exists('pagination', $vars) && $vars['pagination']) {
	$nav .= elgg_view('navigation/pagination',array(
		'baseurl' => $url,
		'offset' => $vars['params']['offset'],
		'count' => $vars['results']['count'],
		'limit' => $vars['params']['limit'],
	));
} else {
	$nav = '';
}

// figure out what we're dealing with.
$type_str = NULL;

if (array_key_exists('type', $vars['params']) && array_key_exists('subtype', $vars['params'])) {
	$type_str_tmp = "item:{$vars['params']['type']}:{$vars['params']['subtype']}";
	$type_str_echoed = elgg_echo($type_str_tmp);
	if ($type_str_echoed != $type_str_tmp) {
		$type_str = $type_str_echoed;
	}
}

if (!$type_str && array_key_exists('type', $vars['params'])) {
	$type_str = elgg_echo("item:{$vars['params']['type']}");
}

if (!$type_str) {
	$type_str = elgg_echo('search:unknown_entity');
}

// allow overrides for titles
$search_type_str = elgg_echo("search_types:{$vars['params']['search_type']}");
if (array_key_exists('search_type', $vars['params'])
	&& $search_type_str != "search_types:{$vars['params']['search_type']}") {

	$type_str = $search_type_str;
}

// get pagination
if (array_key_exists('pagination', $vars['params']) && $vars['params']['pagination']) {
	$nav .= elgg_view('navigation/pagination',array(
		'baseurl' => $url,
		'offset' => $vars['params']['offset'],
		'count' => $vars['results']['count'],
		'limit' => $vars['params']['limit'],
	));
} else {
	$nav = '';
}

// get any more links.
$more_check = $vars['results']['count'] - ($vars['params']['offset'] + $vars['params']['limit']);
$more = ($more_check > 0) ? $more_check : 0;

if ($more) {
	$title_key = ($more == 1) ? 'comment' : 'comments';
	$more_str = elgg_echo('search:more', array($count, $type_str));
	$more_link = "<div class='search_listing'><a href=\"$url\">$more_str</a></div>";
} else {
	$more_link = '';
}

$body = "<div class='search_listing_category_title'>" . elgg_view_title($type_str) . "</div>";

foreach ($entities as $entity) {
	if ($view = search_get_search_view($vars['params'], 'entity')) {
		$body .= elgg_view($view, array(
			'entity' => $entity,
			'params' => $vars['params'],
			'results' => $vars['results']
		));
	}
}
echo $body;
echo $more_link;
echo $nav;
