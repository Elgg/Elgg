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
 * Return default results for searches on objects.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown_type
 */
function search_objects_hook($hook, $type, $value, $params) {
	global $CONFIG;

	$join = "JOIN {$CONFIG->dbprefix}objects_entity oe ON e.guid = oe.guid";
	$params['joins'] = array($join);
	$fields = array('title', 'description');

	$where = search_get_where_sql('oe', $fields, $params);

	$params['wheres'] = array($where);

	//$params['order_by'] = search_get_order_by_sql('oe', $params['sort'], $params['order']);

	$entities = elgg_get_entities($params);
	$params['count'] = TRUE;
	$count = elgg_get_entities($params);

	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
		//$title = search_get_highlighted_relevant_substrings($entity->title, $params['query']);
		//$title = search_get_relevant_substring($entity->title, $params['query'], '<strong class="searchMatch">', '</strong>');
		$title = search_get_highlighted_relevant_substrings($entity->title, $params['query']);
		$entity->setVolatileData('search_matched_title', $title);

		//$desc = search_get_relevant_substring($entity->description, $params['query'], '<strong class="searchMatch">', '</strong>');
		$desc = search_get_highlighted_relevant_substrings($entity->description, $params['query']);
		$entity->setVolatileData('search_matched_description', $desc);
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

/**
 * Return default results for searches on groups.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown_type
 */
function search_groups_hook($hook, $type, $value, $params) {
	global $CONFIG;

	$query = sanitise_string($params['query']);

	$join = "JOIN {$CONFIG->dbprefix}groups_entity ge ON e.guid = ge.guid";
	$params['joins'] = array($join);

	$where = "(ge.guid = e.guid
		AND (ge.name LIKE '%$query%'
			OR ge.description LIKE '%$query%'
			)
		)";
	$params['wheres'] = array($where);

	$entities = elgg_get_entities($params);
	$params['count'] = TRUE;
	$count = elgg_get_entities($params);

	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
		$description = search_get_highlighted_relevant_substrings($entity->description, $query);
		$entity->setVolatileData('search_matched_title', $description);

		$name = search_get_highlighted_relevant_substrings($entity->name, $query);
		$entity->setVolatileData('search_matched_description', $name);
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

/**
 * Return default results for searches on users.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown_type
 */
function search_users_hook($hook, $type, $value, $params) {
	global $CONFIG;

	$query = sanitise_string($params['query']);

	$join = "JOIN {$CONFIG->dbprefix}users_entity ue ON e.guid = ue.guid";
	$params['joins'] = array($join);

	$where = "(ue.guid = e.guid
		AND (ue.username LIKE '%$query%'
			OR ue.name LIKE '%$query%'
			)
		)";
	$params['wheres'] = array($where);

	$entities = elgg_get_entities($params);
	$params['count'] = TRUE;
	$count = elgg_get_entities($params);

	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
		$username = search_get_highlighted_relevant_substrings($entity->username, $query);
		$entity->setVolatileData('search_matched_title', $username);

		$name = search_get_highlighted_relevant_substrings($entity->name, $query);
		$entity->setVolatileData('search_matched_description', $name);
	}

	return array(
		'entities' => $entities,
		'count' => $count,
	);
}

/**
 * Return default results for searches on tags.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown_type
 */
function search_tags_hook($hook, $type, $value, $params) {
	global $CONFIG;

	// @todo will need to split this up to support searching multiple tags at once.
	$query = sanitise_string($params['query']);
	$params['metadata_name_value_pair'] = array ('name' => 'tags', 'value' => $query, 'case_sensitive' => FALSE);

	$entities = elgg_get_entities_from_metadata($params);
	$params['count'] = TRUE;
	$count = elgg_get_entities_from_metadata($params);

	// no need to continue if nothing here.
	if (!$count) {
		return array('entities' => array(), 'count' => $count);
	}

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
		$tags = $entity->tags;

		if (is_array($tags)) {
			$tags = implode(', ', $entity->tags);
		}

		// Nick told me my idea was dirty, so I'm hard coding the numbers.
		$title_tmp = strip_tags($entity->title);
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

		$tags_str = search_get_highlighted_relevant_substrings($tags, $params['query']);
		$tags_str = '(' . elgg_echo('tags') . ": $tags_str)";

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
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown_type
 */
function search_custom_types_tags_hook($hook, $type, $value, $params) {
	$value[] = 'tags';
	return $value;
}


/**
 * Return default results for searches on comments.
 *
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown_type
 */
function search_comments_hook($hook, $type, $value, $params) {
	global $CONFIG;

	$query = sanitise_string($params['query']);
	$params['annotation_names'] = array('generic_comment', 'group_topic_post');

	$params['joins'] = array(
		"JOIN {$CONFIG->dbprefix}annotations a on e.guid = a.entity_guid",
		"JOIN {$CONFIG->dbprefix}metastrings msn on a.name_id = msn.id",
		"JOIN {$CONFIG->dbprefix}metastrings msv on a.value_id = msv.id"
	);

	$fields = array('string');

	// force IN BOOLEAN MODE since fulltext isn't
	// available on metastrings (and boolean mode doesn't need it)
	$search_where = search_get_where_sql('msv', $fields, $params, FALSE);

	$e_access = get_access_sql_suffix('e');
	$a_access = get_access_sql_suffix('a');
	// @todo this can probably be done through the api..
	$q = "SELECT DISTINCT a.*, msv.string as comment FROM {$CONFIG->dbprefix}annotations a
		JOIN {$CONFIG->dbprefix}metastrings msn ON a.name_id = msn.id
		JOIN {$CONFIG->dbprefix}metastrings msv ON a.value_id = msv.id
		JOIN {$CONFIG->dbprefix}entities e ON a.entity_guid = e.guid
		WHERE msn.string IN ('generic_comment', 'group_topic_post')
			AND ($search_where)
			AND $e_access
			AND $a_access

		LIMIT {$params['offset']}, {$params['limit']}
		";

	$comments = get_data($q);

//elgg_get_entities()
	$q = "SELECT count(DISTINCT a.id) as total FROM {$CONFIG->dbprefix}annotations a
		JOIN {$CONFIG->dbprefix}metastrings msn ON a.name_id = msn.id
		JOIN {$CONFIG->dbprefix}metastrings msv ON a.value_id = msv.id
		JOIN {$CONFIG->dbprefix}entities e ON a.entity_guid = e.guid
		WHERE msn.string IN ('generic_comment', 'group_topic_post')
			AND ($search_where)
			AND $e_access
			AND $a_access
		";

	$result = get_data($q);
	$count = $result[0]->total;

	if (!is_array($comments)) {
		return FALSE;
	}

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
 * @param unknown_type $hook
 * @param unknown_type $type
 * @param unknown_type $value
 * @param unknown_type $params
 * @return unknown_type
 */
function search_custom_types_comments_hook($hook, $type, $value, $params) {
	$value[] = 'comments';
	return $value;
}