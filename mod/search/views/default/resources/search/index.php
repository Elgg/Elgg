<?php

/**
 * Elgg search page
 *
 * @todo much of this code should be pulled out into a library of functions
 */
// Search supports RSS
elgg_register_rss_link();

$params = search_prepare_search_params();
$container_guid = elgg_extract('container_guid', $params);
if ($container_guid && !is_array($container_guid)) {
	elgg_set_page_owner_guid($container_guid);
	elgg_group_gatekeeper(true);
}

$query = elgg_extract('query', $params);
$display_query = _elgg_get_display_query($query);
if (!elgg_extract('tokenize', $params)) {
	$searched_words = [$display_query];
} else {
	$searched_words = search_remove_ignored_words($display_query, 'array');
}

$highlighted_query = search_highlight_words($searched_words, $display_query);

$title = elgg_echo('search:results', ["\"$highlighted_query\""]);

$form = elgg_view_form('search', [
	'action' => elgg_normalize_url('search'),
	'method' => 'get',
	'disable_security' => true,
		], $params);

if (empty($query) && $query != "0") {
	// display a search form if there is no query
	$layout = elgg_view_layout('content', array(
		'title' => elgg_echo('search'),
		'content' => $form,
		'filter' => '',
	));

	echo elgg_view_page(elgg_echo('search'), $layout);
	return;
}

$use_type = function($search_type, $type = null, $subtype = null) use ($params) {

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

$get_list = function($search_type, $type = null, $subtype = null, $count = false) use ($params) {
	$current_params = $params;
	$current_params['search_type'] = $search_type;
	$current_params['type'] = $type;
	$current_params['subtype'] = $subtype;

	switch ($search_type) {
		case 'entities' :
			if ($subtype && _elgg_services()->hooks->hasHandler('search', "$type:$subtype")) {
				$hook_type = "$type:$subtype";
			} else {
				$hook_type = $type;
			}
			break;

		default :
			$hook_type = $search_type;
			break;
	}

	$results = [
		'entities' => [],
		'count' => 0,
	];

	if (_elgg_services()->hooks->hasHandler('search', $hook_type)) {
		elgg_deprecated_notice("
			'search','$hook_type' plugin hook has been deprecated and may be removed.
			Please consult the documentation for the new core search API
			and update your use of search hooks.
		", '3.0');
		$results = elgg_trigger_plugin_hook('search', $hook_type, $current_params, $results);
		if ($count) {
			return (int) $results['count'];
		}
	} else {
		$current_params['count'] = true;
		$results['count'] = (int) elgg_search($current_params);
		if ($count) {
			return $results['count'];
		}
		if (!empty($results['count'])) {
			unset($current_params['count']);
			$results['entities'] = elgg_search($current_params);
		}
	}

	if (empty($results['entities'])) {
		return '';
	}

	return elgg_view('search/list', array(
		'results' => $results,
		'params' => $current_params,
	));
};

$total = 0;

$types = search_get_type_subtype_pairs($params);
foreach ($types as $type => $subtypes) {
	if (!empty($subtypes)) {
		foreach ($subtypes as $subtype) {
			$count = $get_list('entities', $type, $subtype, true);
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
				$results .= $get_list('entities', $type, $subtype);
			}
		}
	} else {
		$count = $get_list('entities', $type, null, true);
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
			$results .= $get_list('entities', $type);
		}
	}
}

$custom_types = search_get_search_types($params);
foreach ($custom_types as $search_type) {
	$count = $get_list($search_type, null, null, true);
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
		$results .= $get_list($search_type);
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
			], elgg_echo('search:no_results'));
}

$layout = elgg_view_layout('content', [
	'title' => $title,
	'content' => $form . $results,
	'filter' => '',
		]);

echo elgg_view_page(elgg_echo('search'), $layout);
