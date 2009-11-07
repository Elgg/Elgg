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

	//@todo allow sorting by recent time
	$params['order_by'] = NULL;

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
		$title = search_get_relevant_substring($entity->title, $params['query'], '<strong class="searchMatch">', '</strong>');
		$entity->setVolatileData('search_matched_title', $title);

		$desc = search_get_relevant_substring($entity->description, $params['query'], '<strong class="searchMatch">', '</strong>');
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

	$query = $params['query'];

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
		$description = search_get_relevant_substring($entity->description, $query, '<strong class="searchMatch">', '</strong>');
		$entity->setVolatileData('search_matched_title', $description);

		$name = search_get_relevant_substring($entity->name, $query, '<strong class="searchMatch">', '</strong>');
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

	$query = $params['query'];

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
		$username = search_get_relevant_substring($entity->username, $query, '<strong class="searchMatch">', '</strong>');
		$entity->setVolatileData('search_matched_title', $username);

		$name = search_get_relevant_substring($entity->name, $query, '<strong class="searchMatch">', '</strong>');
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

	$query = $params['query'];
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
		$tags = implode(',', $entity->tags);
		$tags_str = search_get_relevant_substring($tags, $query, '<strong class="searchMatch">', '</strong>');
		$entity->setVolatileData('search_matched_tags', $tags_str);
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

