<?php
/**
 * Elgg search page
 *
 * @todo much of this code should be pulled out into a library of functions
 */

// Search supports RSS
elgg_register_rss_link();

// $search_type == all || entities || trigger plugin hook
$search_type = get_input('search_type', 'all');

// @todo there is a bug in get_input that makes variables have slashes sometimes.
// @todo is there an example query to demonstrate ^
// XSS protection is more important that searching for HTML.
$query = stripslashes(get_input('q', get_input('tag', '')));

$display_query = _elgg_get_display_query($query);

// check that we have an actual query
if (empty($query) && $query != "0") {
	$title = sprintf(elgg_echo('search:results'), "\"$display_query\"");
	
	$layout = elgg_view_layout('one_sidebar', [
		'title' => elgg_echo('search:search_error'),
		'content' => elgg_echo('search:no_query'),
	]);
	echo elgg_view_page($title, $layout);

	return;
}

// get limit and offset.  override if on search dashboard, where only 2
// of each most recent entity types will be shown.
$limit = ($search_type == 'all') ? 2 : get_input('limit', elgg_get_config('default_limit'));
$offset = ($search_type == 'all') ? 0 : get_input('offset', 0);

$entity_type = get_input('entity_type', ELGG_ENTITIES_ANY_VALUE);
$entity_subtype = get_input('entity_subtype', ELGG_ENTITIES_ANY_VALUE);
$owner_guid = get_input('owner_guid', ELGG_ENTITIES_ANY_VALUE);
$container_guid = get_input('container_guid', ELGG_ENTITIES_ANY_VALUE);

$sort = get_input('sort');
if (!in_array($sort, ['relevance', 'created', 'updated', 'alpha'])) {
	$sort = 'relevance';
}

$order = get_input('order', 'desc');
if (!in_array($order, ['asc', 'desc'])) {
	$order = 'desc';
}

// set up search params
$params = [
	'query' => $query,
	'offset' => $offset,
	'limit' => $limit,
	'sort' => $sort,
	'order' => $order,
	'search_type' => $search_type,
	'type' => $entity_type,
	'subtype' => $entity_subtype,
	'owner_guid' => $owner_guid,
	'container_guid' => $container_guid,
	'pagination' => ($search_type == 'all') ? false : true,
];

$types = get_registered_entity_types();
$types = elgg_trigger_plugin_hook('search_types', 'get_queries', $params, $types);

$custom_types = elgg_trigger_plugin_hook('search_types', 'get_types', $params, []);

// add sidebar items for all and native types
$data = htmlspecialchars(http_build_query([
	'q' => $query,
	'owner_guid' => $owner_guid,
	'search_type' => 'all',
]));

elgg_register_menu_item('page', [
	'name' => 'all',
	'text' => elgg_echo('all'),
	'href' => "search?$data",
]);

foreach ($types as $type => $subtypes) {
	if (is_array($subtypes) && count($subtypes)) {
		foreach ($subtypes as $subtype) {
			$data = htmlspecialchars(http_build_query([
				'q' => $query,
				'entity_subtype' => $subtype,
				'entity_type' => $type,
				'owner_guid' => $owner_guid,
				'search_type' => 'entities',
			]));

			elgg_register_menu_item('page', [
				'name' => "item:$type:$subtype",
				'text' => elgg_echo("item:$type:$subtype"),
				'href' => "search?$data",
			]);
		}
	} else {

		$data = htmlspecialchars(http_build_query([
			'q' => $query,
			'entity_type' => $type,
			'owner_guid' => $owner_guid,
			'search_type' => 'entities',
		]));

		elgg_register_menu_item('page', [
			'name' => "item:$type",
			'text' => elgg_echo("item:$type"),
			'href' => "search?$data",
		]);
	}
}

// add sidebar for custom searches
foreach ($custom_types as $type) {
	$data = htmlspecialchars(http_build_query([
		'q' => $query,
		'search_type' => $type,
	]));

	elgg_register_menu_item('page', [
		'name' => "search_types:$type",
		'text' => elgg_echo("search_types:$type"),
		'href' => "search?$data",
	]);
}

// start the actual search
$results_html = '';

if ($search_type == 'all' || $search_type == 'entities') {
	// to pass the correct current search type to the views
	$current_params = $params;
	$current_params['search_type'] = 'entities';

	// foreach through types.
	// if a plugin returns FALSE for subtype ignore it.
	// if a plugin returns NULL or '' for subtype, pass to generic type search function.
	// if still NULL or '' or empty(array()) no results found. (== don't show??)
	foreach ($types as $type => $subtypes) {
		if ($search_type != 'all' && $entity_type != $type) {
			continue;
		}

		if (is_array($subtypes) && count($subtypes)) {
			foreach ($subtypes as $subtype) {
				// no need to search if we're not interested in these results
				// @todo when using index table, allow search to get full count.
				if ($search_type != 'all' && $entity_subtype != $subtype) {
					continue;
				}
				$current_params['subtype'] = $subtype;
				$current_params['type'] = $type;

				$results = elgg_search($current_params);
				
				$view = search_get_search_view($current_params, 'list');
				if (empty($view)) {
					continue;
				}
				
				$results_html .= elgg_view($view, [
					'results' => $results,
					'params' => $current_params,
				]);
			}
		}

		// pull in default type entities with no subtypes
		$current_params['type'] = $type;
		$current_params['subtype'] = ELGG_ENTITIES_NO_VALUE;

		$results = elgg_search($current_params);
		
		if ($results === false) {
			// someone is saying not to display these types in searches.
			continue;
		}
		
		if (empty($results['entities']) || empty($results['count'])) {
			continue;
		}

		$view = search_get_search_view($current_params, 'list');
		if (empty($view)) {
			continue;
		}
		
		$results_html .= elgg_view($view, [
			'results' => $results,
			'params' => $current_params,
		]);
	}
}

// call custom searches
if (is_array($custom_types) && ($search_type != 'entities' || $search_type == 'all')) {
	foreach ($custom_types as $type) {
		if ($search_type != 'all' && $search_type != $type) {
			continue;
		}

		$current_params = $params;
		$current_params['search_type'] = $type;

		$results = elgg_search($current_params);

		if ($results === false) {
			// someone is saying not to display these types in searches.
			continue;
		}
		
		if (empty($results['entities']) || empty($results['count'])) {
			continue;
		}

		$view = search_get_search_view($current_params, 'list');
		if (empty($view)) {
			continue;
		}

		$results_html .= elgg_view($view, [
			'results' => $results,
			'params' => $current_params,
		]);
	}
}

// create highlighted title
if ($search_type == 'tags') {
	$searched_words = [$display_query];
} else {
	$searched_words = search_remove_ignored_words($display_query, 'array');
}

$highlighted_query = search_highlight_words($searched_words, $display_query);

$highlighted_title = elgg_echo('search:results', ["\"$highlighted_query\""]);

$body = $results_html ?: elgg_view('search/no_results');

// this is passed the original params because we don't care what actually
// matched (which is out of date now anyway).
// we want to know what search type it is.
$layout_view = search_get_search_view($params, 'layout');
$layout = elgg_view($layout_view, [
	'params' => $params, 
	'body' => $body, 
	'title' => $highlighted_title,
]);

$title = elgg_echo('search:results', ["\"$display_query\""]);

echo elgg_view_page($title, $layout);
