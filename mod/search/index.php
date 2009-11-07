<?php

// $search_type == all || entities || trigger plugin hook
$search_type = get_input('search_type', 'all');

// @todo there is a bug in get_input that makes variables have slashes sometimes.
$query = sanitise_string(stripslashes(get_input('q', get_input('tag', '', FALSE), FALSE)));

// get limit and offset.  override if on search dashboard, where only 2
// of each most recent entity types will be shown.
$limit = ($search_type == 'all') ? 2 : get_input('limit', 10);
$offset = ($search_type == 'all') ? 0 : get_input('offset', 0);

$type = get_input('type', '');
$subtype = get_input('subtype', '');
$owner_guid = get_input('owner_guid', NULL);
$friends = (int)get_input('friends', 0);

// set up search params
$params = array(
	'query' => $query,
	'offset' => $offset,
	'limit' => $limit,
	'search_type' => $search_type,
	'type' => $type,
	'subtype' => $subtype,
	'tag_type' => $tag_type,
	'owner_guid' => $owner_guid,
	'friends' => $friends
);

$results_html = '';
if ($search_type == 'entities' || $search_type == 'all') {
	$types = get_registered_entity_types();

	// to pass the correct search type to the views
	$params['search_type'] = 'entities';

	// foreach through types.
	// if a plugin returns FALSE for subtype ignore it.
	// if a plugin returns NULL or '' for subtype, pass to generic type search function.
	// if still NULL or '' or empty(array()) no results found. (== don't show??)
	foreach ($types as $type => $subtypes) {
		if (is_array($subtypes) && count($subtypes)) {
			foreach ($subtypes as $subtype) {
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
if ($search_type == 'all' || $search_type != 'entities') {
	// get custom search types
	$types = trigger_plugin_hook('search_types', 'get_types', $params, array());

	if (is_array($types)) {
		foreach ($types as $type) {
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

//if ($search_type !== 'all') {
//	var_dump('here');
//	$entities = trigger_plugin_hook('search', $search_type, '', $return);
//}
/*

call search_section_start to display long bar with types and titles
call search



*/

$layout = elgg_view_layout('two_column_left_sidebar', '', $results_html);

page_draw($title, $layout);







return;


/** Main search page */

global $CONFIG;

$tag = get_input('tag');
$offset = get_input('offset', 0);
$viewtype = get_input('search_viewtype','list');
if ($viewtype == 'gallery') {
	$limit = get_input('limit', 12); // 10 items in list view
} else {
	$limit = get_input('limit', 10); // 12 items in gallery view
}
$searchtype = get_input('searchtype', 'all');
$type = get_input('type', '');
$subtype = get_input('subtype', '');
$owner_guid = get_input('owner_guid', '');
$tagtype = get_input('tagtype', '');
$friends = (int)get_input('friends', 0);
$title = sprintf(elgg_echo('searchtitle'), $tag);

if (substr_count($owner_guid, ',')) {
	$owner_guid_array = explode(',', $owner_guid);
} else {
	$owner_guid_array = $owner_guid;
}
if ($friends > 0) {
	if ($friends = get_user_friends($friends, '', 9999)) {
		$owner_guid_array = array();
		foreach($friends as $friend) {
			$owner_guid_array[] = $friend->guid;
		}
	} else {
		$owner_guid = -1;
	}
}

// Set up submenus
if ($types = get_registered_entity_types()) {
	foreach($types as $ot => $subtype_array) {
		if (is_array($subtype_array) && count($subtype_array)) {
			foreach($subtype_array as $object_subtype) {
				$label = 'item:' . $ot;
				if (!empty($object_subtype)) {
					$label .= ':' . $object_subtype;
				}

				$data = http_build_query(array(
					'tag' => urlencode($tag),
					'subtype' => $object_subtype,
					'type' => urlencode($ot),
					//'tagtype' => urlencode($md_type),
					'owner_guid' => urlencode($owner_guid)
				));

				$url = "{$CONFIG->wwwroot}pg/search/?$data";

				add_submenu_item(elgg_echo($label), $url);
			}
		}
	}

	$data = http_build_query(array(
		'tag' => urlencode($tag),
		'owner_guid' => urlencode($owner_guid)
	));

	add_submenu_item(elgg_echo('all'), "{$CONFIG->wwwroot}pg/search/?$data");
}

// pull in search types for external or aggregated searches.
if ($search_types = trigger_plugin_hook('search', 'types', '', NULL, array())) {

}

$body = '';
if (!empty($tag)) {
	// start with blank results.
	$results = array(
		'entities' => array(),
		'total' => 0
	);

	// do the actual searchts
	$params = array(
		'tag' => $tag,
		'offset' => $offset,
		'limit' => $limit,
		'searchtype' => $searchtype,
		'type' => $type,
		'subtype' => $subtype,
		'tagtype' => $tagtype,
		'owner_guid' => $owner_guid_array
	);

	$results = trigger_plugin_hook('search', 'entities', $params, $results);

	if (empty($type) && empty($subtype)) {
		$title = sprintf(elgg_echo('searchtitle'),$tag);
	} else {
		if (empty($type)) {
			$type = 'object';
		}
		$itemtitle = 'item:' . $type;
		if (!empty($subtype)) {
			$itemtitle .= ':' . $subtype;
		}
		$itemtitle = elgg_echo($itemtitle);
		$title = sprintf(elgg_echo('advancedsearchtitle'),$itemtitle,$tag);
	}

	$body .= elgg_view_title($title); // elgg_view_title(sprintf(elgg_echo('searchtitle'),$tag));

	// call the old (now-deprecated) search hook here
	$body .= trigger_plugin_hook('search','',$tag, '');

	$body .= elgg_view('search/startblurb', array('query' => $query));

	if ($results->total > 0) {
		$body .= elgg_view('search/entity_list', array(
			'entities' => $results->entities,
			'count' => $results->total,
			'offset' => $offset,
			'limit' => $limit,
			'baseurl' => $_SERVER['REQUEST_URI'],
			'fullview' => false,
			'context' => 'search',
			'viewtypetoggle' => true,
			'viewtype' => $viewtype,
			'pagination' => true
		));
	} else {
		$body .= elgg_view('page_elements/contentwrapper', array('body' => elgg_echo('search:noresults')));
	}

	elgg_view_entity_list($results->entities, count($results->entities), 0, count($results->entities), false);
} else {
	// if no tag was given, give the user a box to input a search term
	$body .= elgg_view_title(elgg_echo('search:enterterm'));
	$body .= elgg_view('page_elements/contentwrapper', array('body' => '<div>' . elgg_view('page_elements/searchbox') . '</div>'));
}

$layout = elgg_view_layout('two_column_left_sidebar','',$body);

page_draw($title, $layout);