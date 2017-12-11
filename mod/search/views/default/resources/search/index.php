<?php

/**
 * Elgg search page
 */

// Search supports RSS
elgg_register_rss_link();

$service = new \Elgg\Search\Search();
$params = $service->getParams();

$container_guid = elgg_extract('container_guid', $params);
if ($container_guid && !is_array($container_guid)) {
	elgg_set_page_owner_guid($container_guid);
	elgg_group_gatekeeper(true);
}

$query = elgg_extract('query', $params);

$highlighted_query = $service->getHighlighter()->highlightWords($query);

$title = elgg_echo('search:results', ["\"$highlighted_query\""]);

$form = elgg_view_form('search', [
	'action' => elgg_normalize_url('search'),
	'method' => 'get',
	'disable_security' => true,
], $params);

if (empty($query) && $query != "0") {
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
	if (!empty($subtypes)) {
		foreach ($subtypes as $subtype) {
			$count = $service->listResults('entities', $type, $subtype, true);
			$total += $count;
			elgg_register_menu_item('page', [
				'name' => "item:$type:$subtype",
				'text' => elgg_echo("item:$type:$subtype"),
				'href' => elgg_http_add_url_query_elements('search', [
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
	} else {
		$count = $service->listResults('entities', $type, null, true);
		$total += $count;
		elgg_register_menu_item('page', [
			'name' => "item:$type",
			'text' => elgg_echo("item:$type"),
			'href' => elgg_http_add_url_query_elements('search', [
				'q' => $params['query'],
				'entity_type' => $type,
				'owner_guid' => $params['owner_guid'],
				'search_type' => 'entities',
			]),
			'badge' => $count,
		]);
		if ($use_type('entities', $type)) {
			$results .= $service->listResults('entities', $type);
		}
	}
}

$custom_types = $service->getSearchTypes();
foreach ($custom_types as $search_type) {
	$count = $service->listResults($search_type, null, null, true);
	$total += $count;
	elgg_register_menu_item('page', [
		'name' => "search_types:$type",
		'text' => elgg_echo("search_types:$type"),
		'href' => elgg_http_add_url_query_elements('search', [
			'q' => $params['query'],
			'search_type' => $type,
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
	'href' => elgg_http_add_url_query_elements('search', [
		'q' => $params['query'],
		'owner_guid' => $params['owner_guid'],
		'search_type' => 'all',
	]),
	'badge' => $total,
	'priority' => 1,
]);

if (empty($results)) {
	$results = elgg_format_element('p', [
		'class' => 'elgg-no-results',
	], elgg_echo('notfound'));
}

$layout = elgg_view_layout('content', [
	'title' => $title,
	'content' => $form . $results,
	'filter' => '',
]);

echo elgg_view_page(elgg_echo('search'), $layout);
