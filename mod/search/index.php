<?php

// $search_type == all || entities || trigger plugin hook
$search_type = get_input('search_type', 'all');

// @todo there is a bug in get_input that makes variables have slashes sometimes.
$query = stripslashes(get_input('q', get_input('tag', '', FALSE), FALSE));

// get limit and offset.  override if on search dashboard, where only 2
// of each most recent entity types will be shown.
$limit = ($search_type == 'all') ? 2 : get_input('limit', 10);
$offset = ($search_type == 'all') ? 0 : get_input('offset', 0);

$entity_type = get_input('entity_type', NULL);
$entity_subtype = get_input('entity_subtype', NULL);
$owner_guid = get_input('owner_guid', NULL);
$friends = (int)get_input('friends', 0);

// set up search params
$params = array(
	'query' => $query,
	'offset' => $offset,
	'limit' => $limit,
	'search_type' => $search_type,
	'type' => $entity_type,
	'subtype' => $entity_subtype,
//	'tag_type' => $tag_type,
	'owner_guid' => $owner_guid,
//	'friends' => $friends
	'pagination' => ($search_type == 'all') ? FALSE : TRUE
);

$types = get_registered_entity_types();
$custom_types = trigger_plugin_hook('search_types', 'get_types', $params, array());

// add submenu items for all and native types
// @todo should these maintain any existing type / subtype filters or reset?
$data = htmlspecialchars(http_build_query(array(
	'q' => $query,
	'entity_subtype' => $subtype,
	'entity_type' => $type,
	'owner_guid' => $owner_guid,
	'search_type' => 'all',
	'friends' => $friends
)));
$url = "{$CONFIG->wwwroot}pg/search/?$data";
add_submenu_item(elgg_echo('all'), $url);

foreach ($types as $type => $subtypes) {
	// @todo when using index table, can include result counts on each of these.
	if (is_array($subtypes) && count($subtypes)) {
		foreach ($subtypes as $subtype) {
			$label = "item:$type:$subtype";

			$data = htmlspecialchars(http_build_query(array(
				'q' => $query,
				'entity_subtype' => $subtype,
				'entity_type' => $type,
				'owner_guid' => $owner_guid,
				'search_type' => 'entities',
				'friends' => $friends
			)));

			$url = "{$CONFIG->wwwroot}pg/search/?$data";

			add_submenu_item(elgg_echo($label), $url);
		}
	} else {
		$label = "item:$type";

		$data = htmlspecialchars(http_build_query(array(
			'q' => $query,
			'entity_type' => $type,
			'owner_guid' => $owner_guid,
			'search_type' => 'entities',
			'friends' => $friends
		)));

		$url = "{$CONFIG->wwwroot}pg/search/?$data";

		add_submenu_item(elgg_echo($label), $url);
	}
}

// add submenu for custom searches
foreach ($custom_types as $type) {
	$label = "search_types:$type";

	$data = htmlspecialchars(http_build_query(array(
		'q' => $query,
		'entity_subtype' => $entity_subtype,
		'entity_type' => $entity_type,
		'owner_guid' => $owner_guid,
		'search_type' => $type,
		'friends' => $friends
	)));

	$url = "{$CONFIG->wwwroot}pg/search/?$data";

	add_submenu_item(elgg_echo($label), $url);
}


// check that we have an actual query
if (!$query) {
	$body  = elgg_view_title(elgg_echo('search:search_error'));
	$body .= elgg_view('page_elements/contentwrapper', array('body' => elgg_echo('search:no_query')));

	$layout = elgg_view_layout('two_column_left_sidebar', '', $body);
	page_draw($title, $layout);

	return;
}

// start the actual search
$results_html = '';

if ($search_type == 'all' || $search_type == 'entities') {
	// to pass the correct search type to the views
	$params['search_type'] = 'entities';

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
				$params['subtype'] = $subtype;
				$params['type'] = $type;

				$entities = trigger_plugin_hook('search', "$type:$subtype", $params, NULL);
				if ($entities === FALSE) {
					// someone is saying not to display these types in searches.
					continue;
				} elseif (is_array($entities) && !count($entities)) {
					// no results, but results searched in hook.
				} elseif (!$entities) {
					// no results and not hooked.  use default type search.
					// don't change the params here, since it's really a different subtype.
					// Will be passed to elgg_get_entities().
					$results = trigger_plugin_hook('search', $type, $params, array());
				}

				if (is_array($results['entities']) && $results['count']) {
					$results_html .= search_get_listing_html($results['entities'], $results['count'], $params);
				}
			}
		}

		// pull in default type entities with no subtypes
		// @todo this might currently means "all entities regardless of subtype"
		$params['type'] = $type;
		$params['subtype'] = 0;

		$results = trigger_plugin_hook('search', $type, $params, array());
		if ($results === FALSE) {
			// someone is saying not to display these types in searches.
			continue;
		}

		if (is_array($results['entities']) && $results['count']) {
			$results_html .= search_get_listing_html($results['entities'], $results['count'], $params);
		}
	}
}

// call custom searches
if ($search_type != 'entities' || $search_type == 'all') {
	// get custom search types
	$types = trigger_plugin_hook('search_types', 'get_types', $params, array());

	if (is_array($types)) {
		foreach ($types as $type) {
			if ($search_type != 'all' && $search_type != $type) {
				continue;
			}

			$params['search_type'] = $type;
			unset($params['subtype']);

			$results = trigger_plugin_hook('search', $type, $params, array());

			if ($results === FALSE) {
				// someone is saying not to display these types in searches.
				continue;
			}

			if (is_array($results['entities']) && $results['count']) {
				$results_html .= search_get_listing_html($results['entities'], $results['count'], $params);
			}
		}
	}
}

// highlight search terms
$searched_words = search_remove_ignored_words($query, 'array');
$highlighted_query = search_highlight_words($searched_words, $query);

$body = elgg_view_title(sprintf(elgg_echo('search:results'), "\"$highlighted_query\""));

if (!$results_html) {
	$body .= elgg_view('page_elements/contentwrapper', array('body' => elgg_echo('search:no_results')));
} else {
	$body .= $results_html;
}

$layout = elgg_view_layout('two_column_left_sidebar', '', $body);

page_draw($title, $layout);
