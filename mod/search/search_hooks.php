<?php
/**
 * Elgg core search.
 *
 * @package Elgg
 * @subpackage Search
 */

/**
 * Get objects that match the search parameters.
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Empty array
 * @param array  $params Search parameters
 * @return array
 */
function search_objects_hook($hook, $type, $value, $params) {

	$db_prefix = elgg_get_config('dbprefix');

	$join = "JOIN {$db_prefix}objects_entity oe ON e.guid = oe.guid";
	$params['joins'] = array($join);
	$fields = array('title', 'description');

	$where = search_get_where_sql('oe', $fields, $params);

	$params['wheres'] = array($where);
	$params['count'] = TRUE;
	$count = elgg_get_entities($params);
	
	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}
	
	$params['count'] = FALSE;
	$params['order_by'] = search_get_order_by_sql('e', 'oe', $params['sort'], $params['order']);
	$entities = elgg_get_entities($params);

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
		$title = search_get_highlighted_relevant_substrings($entity->title, $params['query']);
		$entity->setVolatileData('search_matched_title', $title);

		$desc = search_get_highlighted_relevant_substrings($entity->description, $params['query']);
		$entity->setVolatileData('search_matched_description', $desc);
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

/**
 * Get groups that match the search parameters.
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Empty array
 * @param array  $params Search parameters
 * @return array
 */
function search_groups_hook($hook, $type, $value, $params) {
	$db_prefix = elgg_get_config('dbprefix');

	$query = sanitise_string($params['query']);

	$join = "JOIN {$db_prefix}groups_entity ge ON e.guid = ge.guid";
	$params['joins'] = array($join);
	$fields = array('name', 'description');

	$where = search_get_where_sql('ge', $fields, $params);

	$params['wheres'] = array($where);

	// override subtype -- All groups should be returned regardless of subtype.
	$params['subtype'] = ELGG_ENTITIES_ANY_VALUE;

	$params['count'] = TRUE;
	$count = elgg_get_entities($params);
	
	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}
	
	$params['count'] = FALSE;
	$params['order_by'] = search_get_order_by_sql('e', 'ge', $params['sort'], $params['order']);
	$entities = elgg_get_entities($params);

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
		$name = search_get_highlighted_relevant_substrings($entity->name, $query);
		$entity->setVolatileData('search_matched_title', $name);

		$description = search_get_highlighted_relevant_substrings($entity->description, $query);
		$entity->setVolatileData('search_matched_description', $description);
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

/**
 * Get users that match the search parameters.
 *
 * Searches on username, display name, and profile fields
 * 
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Empty array
 * @param array  $params Search parameters
 * @return array
 */
function search_users_hook($hook, $type, $value, $params) {
	$db_prefix = elgg_get_config('dbprefix');

	$query = sanitise_string($params['query']);

	$params['joins'] = array(
		"JOIN {$db_prefix}users_entity ue ON e.guid = ue.guid",
		"JOIN {$db_prefix}metadata md on e.guid = md.entity_guid",
		"JOIN {$db_prefix}metastrings msv ON n_table.value_id = msv.id"
	);
		
	// username and display name
	$fields = array('username', 'name');
	$where = search_get_where_sql('ue', $fields, $params, FALSE);

	// profile fields
	$profile_fields = array_keys(elgg_get_config('profile_fields'));
	
	// get the where clauses for the md names
	// can't use egef_metadata() because the n_table join comes too late.
	$clauses = elgg_entities_get_metastrings_options('metadata', array(
		'metadata_names' => $profile_fields,
	));

	$params['joins'] = array_merge($clauses['joins'], $params['joins']);
	// no fulltext index, can't disable fulltext search in this function.
	// $md_where .= " AND " . search_get_where_sql('msv', array('string'), $params, FALSE);
	$md_where = "(({$clauses['wheres'][0]}) AND msv.string LIKE '%$query%')";
	
	$params['wheres'] = array("(($where) OR ($md_where))");

	// override subtype -- All users should be returned regardless of subtype.
	$params['subtype'] = ELGG_ENTITIES_ANY_VALUE;
	$params['count'] = true;
	$count = elgg_get_entities($params);

	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}
	
	$params['count'] = FALSE;
	$params['order_by'] = search_get_order_by_sql('e', 'ue', $params['sort'], $params['order']);
	$entities = elgg_get_entities($params);

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
		
		$title = search_get_highlighted_relevant_substrings($entity->name, $query);

		// include the username if it matches but the display name doesn't.
		if (false !== strpos($entity->username, $query)) {
			$username = search_get_highlighted_relevant_substrings($entity->username, $query);
			$title .= " ($username)";
		}

		$entity->setVolatileData('search_matched_title', $title);

		$matched = '';
		foreach ($profile_fields as $md_name) {
			$metadata = $entity->$md_name;
			if (is_array($metadata)) {
				foreach ($metadata as $text) {
					if (stristr($text, $query)) {
						$matched .= elgg_echo("profile:{$md_name}") . ': '
								. search_get_highlighted_relevant_substrings($text, $query);
					}
				}
			} else {
				if (stristr($metadata, $query)) {
					$matched .= elgg_echo("profile:{$md_name}") . ': '
							. search_get_highlighted_relevant_substrings($metadata, $query);
				}
			}
		}

		$entity->setVolatileData('search_matched_description', $matched);
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

/**
 * Get entities with tags that match the search parameters.
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Empty array
 * @param array  $params Search parameters
 * @return array
 */
function search_tags_hook($hook, $type, $value, $params) {
	$db_prefix = elgg_get_config('dbprefix');

	$valid_tag_names = elgg_get_registered_tag_metadata_names();

	// @todo will need to split this up to support searching multiple tags at once.
	$query = sanitise_string($params['query']);

	// if passed a tag metadata name, only search on that tag name.
	// tag_name isn't included in the params because it's specific to
	// tag searches.
	if ($tag_names = get_input('tag_names')) {
		if (is_array($tag_names)) {
			$search_tag_names = $tag_names;
		} else {
			$search_tag_names = array($tag_names);
		}

		// check these are valid to avoid arbitrary metadata searches.
		foreach ($search_tag_names as $i => $tag_name) {
			if (!in_array($tag_name, $valid_tag_names)) {
				unset($search_tag_names[$i]);
			}
		}
	} else {
		$search_tag_names = $valid_tag_names;
	}

	if (!$search_tag_names) {
		return array('entities' => array(), 'count' => $count);
	}

	// don't use elgg_get_entities_from_metadata() here because of
	// performance issues.  since we don't care what matches at this point
	// use an IN clause to grab everything that matches at once and sort
	// out the matches later.
	$params['joins'][] = "JOIN {$db_prefix}metadata md on e.guid = md.entity_guid";
	$params['joins'][] = "JOIN {$db_prefix}metastrings msn on md.name_id = msn.id";
	$params['joins'][] = "JOIN {$db_prefix}metastrings msv on md.value_id = msv.id";

	$access = get_access_sql_suffix('md');
	$sanitised_tags = array();

	foreach ($search_tag_names as $tag) {
		$sanitised_tags[] = '"' . sanitise_string($tag) . '"';
	}

	$tags_in = implode(',', $sanitised_tags);

	$params['wheres'][] = "(msn.string IN ($tags_in) AND msv.string = '$query' AND $access)";

	$params['count'] = TRUE;
	$count = elgg_get_entities($params);

	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}
	
	$params['count'] = FALSE;
	$params['order_by'] = search_get_order_by_sql('e', null, $params['sort'], $params['order']);
	$entities = elgg_get_entities($params);

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
		$matched_tags_strs = array();

		// get tags for each tag name requested to find which ones matched.
		foreach ($search_tag_names as $tag_name) {
			$tags = $entity->getTags($tag_name);

			// @todo make one long tag string and run this through the highlight
			// function.  This might be confusing as it could chop off
			// the tag labels.
			if (in_array(strtolower($query), array_map('strtolower', $tags))) {
				if (is_array($tags)) {
					$tag_name_str = elgg_echo("tag_names:$tag_name");
					$matched_tags_strs[] = "$tag_name_str: " . implode(', ', $tags);
				}
			}
		}

		// different entities have different titles
		switch($entity->type) {
			case 'site':
			case 'user':
			case 'group':
				$title_tmp = $entity->name;
				break;

			case 'object':
				$title_tmp = $entity->title;
				break;
		}

		// Nick told me my idea was dirty, so I'm hard coding the numbers.
		$title_tmp = strip_tags($title_tmp);
		if (elgg_strlen($title_tmp) > 297) {
			$title_str = elgg_substr($title_tmp, 0, 297) . '...';
		} else {
			$title_str = $title_tmp;
		}

		$desc_tmp = strip_tags($entity->description);
		if (elgg_strlen($desc_tmp) > 297) {
			$desc_str = elgg_substr($desc_tmp, 0, 297) . '...';
		} else {
			$desc_str = $desc_tmp;
		}

		$tags_str = implode('. ', $matched_tags_strs);
		$tags_str = search_get_highlighted_relevant_substrings($tags_str, $params['query'], 30, 300, true);

		$entity->setVolatileData('search_matched_title', $title_str);
		$entity->setVolatileData('search_matched_description', $desc_str);
		$entity->setVolatileData('search_matched_extra', $tags_str);
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

/**
 * Register tags as a custom search type.
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Array of custom search types
 * @param array  $params Search parameters
 * @return array
 */
function search_custom_types_tags_hook($hook, $type, $value, $params) {
	$value[] = 'tags';
	return $value;
}


/**
 * Get comments that match the search parameters.
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Empty array
 * @param array  $params Search parameters
 * @return array
 */
function search_comments_hook($hook, $type, $value, $params) {
	$db_prefix = elgg_get_config('dbprefix');

	$query = sanitise_string($params['query']);
	$limit = sanitise_int($params['limit']);
	$offset = sanitise_int($params['offset']);
	$params['annotation_names'] = array('generic_comment', 'group_topic_post');

	$params['joins'] = array(
		"JOIN {$db_prefix}annotations a on e.guid = a.entity_guid",
		"JOIN {$db_prefix}metastrings msn on a.name_id = msn.id",
		"JOIN {$db_prefix}metastrings msv on a.value_id = msv.id"
	);

	$fields = array('string');

	// force IN BOOLEAN MODE since fulltext isn't
	// available on metastrings (and boolean mode doesn't need it)
	$search_where = search_get_where_sql('msv', $fields, $params, FALSE);

	$container_and = '';
	if ($params['container_guid'] && $params['container_guid'] !== ELGG_ENTITIES_ANY_VALUE) {
		$container_and = 'AND e.container_guid = ' . sanitise_int($params['container_guid']);
	}

	$e_access = get_access_sql_suffix('e');
	$a_access = get_access_sql_suffix('a');
	// @todo this can probably be done through the api..
	$q = "SELECT count(DISTINCT a.id) as total FROM {$db_prefix}annotations a
		JOIN {$db_prefix}metastrings msn ON a.name_id = msn.id
		JOIN {$db_prefix}metastrings msv ON a.value_id = msv.id
		JOIN {$db_prefix}entities e ON a.entity_guid = e.guid
		WHERE msn.string IN ('generic_comment', 'group_topic_post')
			AND ($search_where)
			AND $e_access
			AND $a_access
			$container_and
		";

	if (!$result = get_data($q)) {
		return FALSE;
	}
	
	$count = $result[0]->total;
	
	// don't continue if nothing there...
	if (!$count) {
		return array ('entities' => array(), 'count' => 0);
	}
	
	$order_by = search_get_order_by_sql('e', null, $params['sort'], $params['order']);
	if ($order_by) {
		$order_by = "ORDER BY $order_by";
	}
	
	$q = "SELECT DISTINCT a.*, msv.string as comment FROM {$db_prefix}annotations a
		JOIN {$db_prefix}metastrings msn ON a.name_id = msn.id
		JOIN {$db_prefix}metastrings msv ON a.value_id = msv.id
		JOIN {$db_prefix}entities e ON a.entity_guid = e.guid
		WHERE msn.string IN ('generic_comment', 'group_topic_post')
			AND ($search_where)
			AND $e_access
			AND $a_access
			$container_and
		
		$order_by
		LIMIT $offset, $limit
		";

	$comments = get_data($q);

	// @todo if plugins are disabled causing subtypes
	// to be invalid and there are comments on entities of those subtypes,
	// the counts will be wrong here and results might not show up correctly,
	// especially on the search landing page, which only pulls out two results.

	// probably better to check against valid subtypes than to do what I'm doing.

	// need to return actual entities
	// add the volatile data for why these entities have been returned.
	$entities = array();
	foreach ($comments as $comment) {
		$entity = get_entity($comment->entity_guid);

		// hic sunt dracones
		if (!$entity) {
			//continue;
			$entity = new ElggObject();
			$entity->setVolatileData('search_unavailable_entity', TRUE);
		}

		$comment_str = search_get_highlighted_relevant_substrings($comment->comment, $query);
		$entity->setVolatileData('search_match_annotation_id', $comment->id);
		$entity->setVolatileData('search_matched_comment', $comment_str);
		$entity->setVolatileData('search_matched_comment_owner_guid', $comment->owner_guid);
		$entity->setVolatileData('search_matched_comment_time_created', $comment->time_created);
		$entities[] = $entity;
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

/**
 * Register comments as a custom search type.
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Array of custom search types
 * @param array  $params Search parameters
 * @return array
 */
function search_custom_types_comments_hook($hook, $type, $value, $params) {
	$value[] = 'comments';
	return $value;
}
