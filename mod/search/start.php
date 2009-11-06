<?php

/**
   * Elgg core search.
   *
   * @package Elgg
   * @subpackage Core
   * @author Curverider Ltd <info@elgg.com>, The MITRE Corporation <http://www.mitre.org>
   * @link http://elgg.org/
   */

/**
 * Initialise search helper functions.
 *
 */
function search_init() {
	// page handler for search actions and results
	register_page_handler('search','search_page_handler');

	// add in CSS for search elements
	extend_view('css', 'search/css');
}

/**
 * Page handler for search
 *
 * @param array $page Page elements from pain page handler
 */
function search_page_handler($page) {
	global $CONFIG;

	if(!get_input('tag')) {
		set_input('tag', $page[0]);
	}

	include_once($CONFIG->path . "mod/search/index.php");
}

/**
 * Core search hook.
 * Returns an object with two parts:
 *    ->entities: an array of instantiated entities that have been decorated with
 *                volatile "search" data indicating what they matched. These are
 *                the entities to be displayed to the user on this page.
 *    ->total:    total number of entities overall. This function can update this
 *                limit to ask for more pages in the pagination.
 */
function search_original_hook($hook, $type, $returnvalue, $params) {
	global $CONFIG;

	var_dump($CONFIG->hooks);

	$tag = $params['tag'];
	$offset = $params['offset']; // starting page
	$limit = $params['limit']; // number per page
	$searchtype = $params['searchtype']; // the search type we're looking for
	$object_type = $params['object_type'];
	$subtype = $params['subtype'];
	$owner_guid = $params['owner_guid'];
	$tagtype = $params['tagtype'];

	$count = get_entities_from_metadata($tagtype, elgg_strtolower($tag), $object_type, $subtype, $owner_guid, $limit, $offset, "", 0, TRUE, FALSE);
	$ents =  get_entities_from_metadata($tagtype, elgg_strtolower($tag), $object_type, $subtype, $owner_guid, $limit, $offset, "", 0, FALSE, FALSE);

//	$options = array(
//		'metadata_name_value_pair' => array('name' => $params['tagtype'], 'value' => $params['tag'], 'case_sensitive' => false),
//		'offset' => $params['offset'],
//		'limit' => $params['limit'],
//		'type' => $params['object_type'],
//		'subtype' => $params['subtype'],
//		'owner_guid' => $params['owner_guid']
//	);
//
//	$count = elgg_get_entities_from_metadata(array_merge($options, array('count' => TRUE)));
//	$entities = elgg_get_entities_from_metadata($options);

	/*
	 * Foreach entity
	 *	get the metadata keys
	 *	If the value matches, hang onto the key
	 *	add all the matched keys to VolatileData
	 *   This tells us *why* each entity matched
	 */
	foreach ($ents as $ent) {
		$metadata = get_metadata_for_entity($ent->getGUID());
		$matched = array();
		if ($metadata) {
			foreach ($metadata as $tuple) {
				if ($tag === $tuple->value) {
					// This is one of the matching elements
					$matched[] = $tuple->name;
				}
			}
			$ent->setVolatileData('search', $matched);
		}
	}

	// merge in our entities with any coming in from elsewhere
	$returnvalue->entities = array_merge($returnvalue->entities, $ents);

	// expand the total entity count if necessary
	if ($count > $returnvalue->total) {
		$returnvalue->total = $count;
	}

	return $returnvalue;
}

/**
 * Provides default search for registered entity subtypes.
 * Entity types should be dealt with in the entity classes.  (Objects are an exception).
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $returnvalue
 * @param unknown_type $params
 * @return unknown_type
 */
function search_registered_entities($hook, $type, $returnvalue, $params) {
	$entity_types = get_registered_entity_types();
	foreach ($entity_types as $type => $subtypes) {
		if (is_array($subtypes) && count($subtypes)) {

		}

	}
}

/**
 * return our base search types (right now, we have none)
 */
function search_base_search_types_hook($hook, $type, $returnvalue, $params) {
	if (!is_array($returnvalue)) {
		$returnvalue = array();
	}

	return $returnvalue;
}


/**
 * Returns a matching string with $context amount of context, optionally
 * surrounded by $before and $after.
 *
 * If no match is found, restricts string to $context*2 starting from strpos 0.
 *
 * @param str $haystack
 * @param str $needle
 * @param str $before
 * @param str $after
 * @param str $context
 * @return str
 */
function search_get_relevant_substring($haystack, $needle, $before = '', $after = '', $context = 75) {
	$haystack = strip_tags($haystack);
	$needle = strip_tags($needle);

	$pos = strpos(strtolower($haystack), strtolower($needle));

	if ($pos === FALSE) {
		$str = substr($haystack, 0, $context*2);
		if (strlen($haystack) > $context*2) {
			$str .= '...';
		}

		return $str;
	}

	$start_pos = $pos - $context;

	if ($start_pos < 0) {
		$start_pos = 0;
	}

	// get string from -context to +context
	$matched = substr($haystack, $start_pos, $context*2);

	// add elipses to front.
	if ($start_pos > 0) {
		$matched = "...$matched";
	}

	// add elipses to end.
	if ($start_pos + $context < strlen($haystack)) {
		$matched = "$matched...";
	}

	// surround if needed
	if ($before || $after) {
		$matched = str_ireplace($needle, $before . $needle . $after, $matched);
	}

	return $matched;
}



function search_get_listing_html($entities, $count, $params) {
	if (!is_array($entities) || !$count) {
		return FALSE;
	}

	$view_order = array();

	// check if there's a special search view for this type:subtype
	if (isset($params['type']) && $params['type'] && isset($params['subtype']) && $params['subtype']) {
		$view_order[] = "search/{$params['type']}/{$params['subtype']}/listing";
	}

	// also check for the default type
	if (isset($params['type']) && $params['type']) {
		$view_order[] = "search/{$params['type']}/listing";
	}

	// check search types
	if (isset($params['search_type']) && $params['search_type']) {
		$view_order[] = "search/{$params['search_type']}/listing";
	}

	// finally default to a search listing default
	$view_order[] = "search/listing";

	$vars = array(
		'entities' => $entities,
		'count' => $count,
		'params' => $params
	);

	foreach ($view_order as $view) {
		if (elgg_view_exists($view)) {
			return elgg_view($view, $vars);
		}
	}

	return FALSE;
}

/** Register init system event **/

register_elgg_event_handler('init','system','search_init');