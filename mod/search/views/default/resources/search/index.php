<?php

/**
 * Elgg search page
 */

// Search supports RSS
elgg_register_rss_link();

// This magic is needed to support /search/<query>
// but have /search/<query1>?q=<query2> as <query2> be the main search query
set_input('q', get_input('q', elgg_extract('route_query', $vars, null, false)));

$service = new \Elgg\Search\Search();
$params = $service->getParams();

$container_guid = elgg_extract('container_guid', $params);
if ($container_guid && !is_array($container_guid)) {
	elgg_entity_gatekeeper($container_guid);
	
	elgg_set_page_owner_guid($container_guid);
}

$query = elgg_extract('query', $params);

$highlighted_query = $service->getHighlighter()->highlightWords($query);

$title = elgg_echo('search:results', ["\"$highlighted_query\""]);

$form = elgg_view_form('search', [
	'action' => elgg_generate_url('default:search'),
	'method' => 'get',
	'disable_security' => true,
], $params);

if (elgg_is_empty($query)) {
	// display a search form if there is no query
	$layout = elgg_view_layout('content', [
		'title' => elgg_echo('search'),
		'content' => $form,
		'filter' => '',
	]);

	echo elgg_view_page(elgg_echo('search'), $layout);

	return;
}

$use_type = function ($search_type, $type = null, $subtype = null) use ($params) {

	if ($params['search_type'] == 'all') {
		return true;
	}

	switch ($params['search_type']) {
		case 'entities' :
			if ($params['type'] && $params['type'] != $type) {
				return false;
			} else if ($params['subtype'] && $params['subtype'] !== $subtype) {
				return false;
			}

			return true;

		// custom search type
		default :
			return $params['search_type'] == $search_type;
	}
};

$total = 0;
$results = '';

$types = $service->getTypeSubtypePairs();
foreach ($types as $type => $subtypes) {
	if (empty($subtypes) || !is_array($subtypes)) {
		continue;
	}
	
	foreach ($subtypes as $subtype) {
		$count = $service->listResults('entities', $type, $subtype, true);
		$total += $count;
		elgg_register_menu_item('page', [
			'name' => "item:$type:$subtype",
			'text' => elgg_echo("item:$type:$subtype"),
			'href' => elgg_generate_url('default:search', [
				'q' => $params['query'],
				'entity_type' => $type,
				'entity_subtype' => $subtype,
				'owner_guid' => $params['owner_guid'],
				'search_type' => 'entities',
			]),
			'badge' => $count,
		]);

		if ($use_type('entities', $type, $subtype)) {
			$results .= $service->listResults('entities', $type, $subtype);
		}
	}
}

$custom_types = $service->getSearchTypes();
foreach ($custom_types as $search_type) {
	$count = $service->listResults($search_type, null, null, true);
	$total += $count;
	elgg_register_menu_item('page', [
		'name' => "search_types:{$search_type}",
		'text' => elgg_echo("search_types:{$search_type}"),
		'href' => elgg_generate_url('default:search', [
			'q' => $params['query'],
			'search_type' => $search_type,
		]),
		'badge' => $count,
	]);

	if ($use_type($search_type)) {
		$results .= $service->listResults($search_type);
	}
}

elgg_register_menu_item('page', [
	'name' => 'all',
	'text' => elgg_echo('all'),
	'href' => elgg_generate_url('default:search', [
		'q' => $params['query'],
		'owner_guid' => $params['owner_guid'],
		'search_type' => 'all',
	]),
	'badge' => $total,
	'priority' => 1,
]);

if (empty($results)) {
	$results = elgg_view('page/components/no_results', [
		'no_results' => elgg_echo('notfound'),
	]);
}

$layout = elgg_view_layout('content', [
	'title' => $title,
	'content' => $form . $results,
	'filter' => '',
]);

echo elgg_view_page(elgg_echo('search'), $layout);
