<?php
/**
 * Elgg core search.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd <info@elgg.com>, The MITRE Corporation <http://www.mitre.org>
 * @link http://elgg.org/
 */

// Search supports RSS
global $autofeed;
$autofeed = true;

// $search_type == all || entities || trigger plugin hook
$search_type = get_input('search_type', 'all');

// @todo there is a bug in get_input that makes variables have slashes sometimes.
// XSS protection is more important that searching for HTML.
$query = stripslashes(get_input('q', get_input('tag', '')));

// get limit and offset.  override if on search dashboard, where only 2
// of each most recent entity types will be shown.
$limit = ($search_type == 'all') ? 2 : get_input('limit', 10);
$offset = ($search_type == 'all') ? 0 : get_input('offset', 0);

$entity_type = get_input('entity_type', ELGG_ENTITIES_ANY_VALUE);
$entity_subtype = get_input('entity_subtype', ELGG_ENTITIES_ANY_VALUE);
$owner_guid = get_input('owner_guid', ELGG_ENTITIES_ANY_VALUE);
$container_guid = get_input('container_guid', ELGG_ENTITIES_ANY_VALUE);
$friends = get_input('friends', ELGG_ENTITIES_ANY_VALUE);
$sort = get_input('sort');
switch ($sort) {
	case 'relevance':
	case 'created':
	case 'updated':
	case 'action_on':
	case 'alpha':
		break;

	default:
		$sort = 'relevance';
		break;
}

$order = get_input('sort', 'desc');
if ($order != 'asc' && $order != 'desc') {
	$order = 'desc';
}

// set up search params
$params = array(
	'query' => $query,
	'offset' => $offset,
	'limit' => $limit,
	'sort' => $sort,
	'order' => $order,
	'search_type' => $search_type,
	'type' => $entity_type,
	'subtype' => $entity_subtype,
//	'tag_type' => $tag_type,
	'owner_guid' => $owner_guid,
	'container_guid' => $container_guid,
//	'friends' => $friends
	'pagination' => ($search_type == 'all') ? FALSE : TRUE
);

$types = get_registered_entity_types();
$custom_types = trigger_plugin_hook('search_types', 'get_types', $params, array());

// add submenu items for all and native types
// @todo should these maintain any existing type / subtype filters or reset?
$data = htmlspecialchars(http_build_query(array(
	'q' => $query,
	'entity_subtype' => $entity_subtype,
	'entity_type' => $entity_type,
	'owner_guid' => $owner_guid,
	'search_type' => 'all',
	//'friends' => $friends
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

				$results = trigger_plugin_hook('search', "$type:$subtype", $current_params, NULL);
				if ($results === FALSE) {
					// someone is saying not to display these types in searches.
					continue;
				} elseif (is_array($results) && !count($results)) {
					// no results, but results searched in hook.
				} elseif (!$results) {
					// no results and not hooked.  use default type search.
					// don't change the params here, since it's really a different subtype.
					// Will be passed to elgg_get_entities().
					$results = trigger_plugin_hook('search', $type, $current_params, array());
				}

				if (is_array($results['entities']) && $results['count']) {
					if ($view = search_get_search_view($current_params, 'listing')) {
						$results_html .= elgg_view($view, array('results' => $results, 'params' => $current_params));
					}
				}
			}
		}

		// pull in default type entities with no subtypes
		$current_params['type'] = $type;
		$current_params['subtype'] = ELGG_ENTITIES_NO_VALUE;

		$results = trigger_plugin_hook('search', $type, $current_params, array());
		if ($results === FALSE) {
			// someone is saying not to display these types in searches.
			continue;
		}

		if (is_array($results['entities']) && $results['count']) {
			if ($view = search_get_search_view($current_params, 'listing')) {
				$results_html .= elgg_view($view, array('results' => $results, 'params' => $current_params));
			}
		}
	}
}

// call custom searches
if ($search_type != 'entities' || $search_type == 'all') {
	if (is_array($custom_types)) {
		foreach ($custom_types as $type) {
			if ($search_type != 'all' && $search_type != $type) {
				continue;
			}

			$current_params = $params;
			$current_params['search_type'] = $type;
			// custom search types have no subtype.
			unset($current_params['subtype']);

			$results = trigger_plugin_hook('search', $type, $current_params, array());

			if ($results === FALSE) {
				// someone is saying not to display these types in searches.
				continue;
			}

			if (is_array($results['entities']) && $results['count']) {
				if ($view = search_get_search_view($current_params, 'listing')) {
					$results_html .= elgg_view($view, array('results' => $results, 'params' => $current_params));
				}
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

// this is passed the original params because we don't care what actually
// matched (which is out of date now anyway).
// we want to know what search type it is.
$layout_view = search_get_search_view($params, 'layout');
$layout = elgg_view($layout_view, array('params' => $params, 'body' => $body));

$title = sprintf(elgg_echo('search:results'), "\"{$params['query']}\"");

page_draw($title, $layout);
