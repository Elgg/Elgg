<?php

namespace Elgg\Search;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use elgg_search instead.
 *
 * @access private
 * @since 3.0
 */
class SearchHandler {

	/**
	 * Returns ORDER BY sql for insertion into elgg_get_entities().
	 *
	 * @param string $entities_table Prefix for entities table.
	 * @param string $type_table     Prefix for the type table.
	 * @param string $sort           ORDER BY part
	 * @param string $order          ASC or DESC
	 * 
	 * @return string
	 */
	private static function getOrderBySQL($entities_table, $type_table, $sort, $order) {
	
		$on = '';
	
		switch ($sort) {
			
			case 'created':
				$on = "$entities_table.time_created";
				break;
			case 'updated':
				$on = "$entities_table.time_updated";
				break;
			case 'alpha':
				// @todo not support yet because both title
				// and name columns are used for this depending
				// on the entity, which we don't always know.  >:O
			case 'relevance':
			default:
				// default is relevance descending.
				// ascending relevancy is silly and complicated.
				return '';
		}
		
		if (!in_array(strtolower($order), ['asc', 'desc'])) {
			$order = 'DESC';
		}
	
		return "$on $order";
	}
	
	/**
	 * Returns a where clause for a search query.
	 *
	 * @param string $table  Prefix for table to search on
	 * @param array  $fields Fields to match against
	 * @param array  $params Original search params
	 *
	 * @return string
	 *
	 * @access private
	 */
	private static function getWhereSQL($table, $fields, $params) {
		$query = elgg_extract('query', $params);
		
		// add the table prefix to the fields
		foreach ($fields as $i => $field) {
			if ($table) {
				$fields[$i] = "$table.$field";
			}
		}
		
		$likes = [];
		
		$query_parts = explode(' ', $query);
		foreach ($fields as $field) {
			$sublikes = [];
			
			foreach ($query_parts as $query_part) {
				$query_part = sanitise_string($query_part);
				
				if (strlen($query_part) == 0) {
					continue;
				}
				
				$sublikes[] = "$field LIKE '%$query_part%'";
			}
			
			$likes[] = '(' . implode(' AND ', $sublikes) . ')';
		}
		
		$likes_str = implode(' OR ', $likes);
		
		return "($likes_str)";
	}
	
	/**
	 * Get objects that match the search parameters.
	 *
	 * @param string $hook   Hook name
	 * @param string $type   Hook type
	 * @param array  $value  Empty array
	 * @param array  $params Search parameters
	 * @return array
	 */
	public static function findObjects($hook, $type, $value, $params) {
		if (!empty($value)) {
			// someone else is providing results... 
			return;
		}
		
		$params['joins'] = (array) elgg_extract('joins', $params, []);
		$params['wheres'] = (array) elgg_extract('wheres', $params, []);
		
		$db_prefix = elgg_get_config('dbprefix');
	
		$join = "JOIN {$db_prefix}objects_entity oe ON e.guid = oe.guid";
		array_unshift($params['joins'], $join);
	
		$fields = ['title', 'description'];
		$where = self::getWhereSQL('oe', $fields, $params);
		$params['wheres'][] = $where;
		
		$params['count'] = true;
		$count = elgg_get_entities($params);
		
		// no need to continue if nothing here.
		if (!$count) {
			return [
				'entities' => [],
				'count' => 0,
			];
		}
		
		$params['count'] = false;
		if (isset($params['sort']) || !isset($params['order_by'])) {
			$params['order_by'] = self::getOrderBySQL('e', 'oe', $params['sort'], $params['order']);
		}
		$params['preload_owners'] = true;
	
		return [
			'entities' => elgg_get_entities($params),
			'count' => $count,
		];
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
	public static function findGroups($hook, $type, $value, $params) {
		if (!empty($value)) {
			// someone else is providing results... 
			return;
		}
		
		$params['joins'] = (array) elgg_extract('joins', $params, []);
		$params['wheres'] = (array) elgg_extract('wheres', $params, []);
		
		$db_prefix = elgg_get_config('dbprefix');
	
		$query = sanitise_string($params['query']);
	
		$join = "JOIN {$db_prefix}groups_entity ge ON e.guid = ge.guid";
		array_unshift($params['joins'], $join);
		
		$fields = ['name', 'description'];
		$where = self::getWhereSQL('ge', $fields, $params);
		$params['wheres'][] = $where;
	
		$params['count'] = true;
		$count = elgg_get_entities($params);
		
		// no need to continue if nothing here.
		if (!$count) {
			return [
				'entities' => [],
				'count' => 0,
			];
		}
		
		$params['count'] = false;
		if (isset($params['sort']) || !isset($params['order_by'])) {
			$params['order_by'] = self::getOrderBySQL('e', 'ge', $params['sort'], $params['order']);
		}
					
		return [
			'entities' => elgg_get_entities($params),
			'count' => $count,
		];
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
	public static function findUsers($hook, $type, $value, $params) {
		if (!empty($value)) {
			// someone else is providing results... 
			return;
		}
		
		$params['joins'] = (array) elgg_extract('joins', $params, []);
		$params['wheres'] = (array) elgg_extract('wheres', $params, []);
		
		$db_prefix = elgg_get_config('dbprefix');
	
		$query = sanitise_string($params['query']);
	
		$join = "JOIN {$db_prefix}users_entity ue ON e.guid = ue.guid";
		array_unshift($params['joins'], $join);
			
		// username and display name
		$fields = ['username', 'name'];
		$where = self::getWhereSQL('ue', $fields, $params);
	
		// profile fields
		$profile_fields = array_keys(elgg_get_config('profile_fields'));
		
		if (!empty($profile_fields)) {
			$params['joins'][] = "JOIN {$db_prefix}metadata md on e.guid = md.entity_guid";
			
			// get the where clauses for the md names
			// can't use egef_metadata() because the n_table join comes too late.
			$clauses = _elgg_entities_get_metastrings_options('metadata', [
				'metadata_names' => $profile_fields,
	
				// avoid notices
				'metadata_values' => null,
				'metadata_name_value_pairs' => null,
				'metadata_name_value_pairs_operator' => null,
				'metadata_case_sensitive' => null,
				'order_by_metadata' => null,
				'metadata_owner_guids' => null,
			]);
		
			$params['joins'] = array_merge($clauses['joins'], $params['joins']);
			
			$md_where = "(({$clauses['wheres'][0]}) AND md.value LIKE '%$query%')";
			
			$params['wheres'][] = "(($where) OR ($md_where))";
		} else {
			$params['wheres'][] = "$where";
		}
	
		$params['count'] = true;
		$count = elgg_get_entities($params);
	
		// no need to continue if nothing here.
		if (!$count) {
			return [
				'entities' => [],
				'count' => $count,
			];
		}
		
		$params['count'] = false;
		if (isset($params['sort']) || !isset($params['order_by'])) {
			$params['order_by'] = self::getOrderBySQL('e', 'ue', $params['sort'], $params['order']);
		}
	
		return [
			'entities' => elgg_get_entities($params),
			'count' => $count,
		];
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
	public static function findEntitiesByTag($hook, $type, $value, $params) {
		if (!empty($value)) {
			// someone else is providing results... 
			return;
		}
		
		$params['joins'] = (array) elgg_extract('joins', $params, []);
		$params['wheres'] = (array) elgg_extract('wheres', $params, []);
		
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
			return [
				'entities' => [],
				'count' => $count,
			];
		}
	
		// don't use elgg_get_entities_from_metadata() here because of
		// performance issues.  since we don't care what matches at this point
		// use an IN clause to grab everything that matches at once and sort
		// out the matches later.
		$db_prefix = elgg_get_config('dbprefix');
		
		$params['joins'][] = "JOIN {$db_prefix}metadata md on e.guid = md.entity_guid";
	
		$access = _elgg_get_access_where_sql(['table_alias' => 'md']);
		$sanitised_tags = [];
	
		foreach ($search_tag_names as $tag) {
			$sanitised_tags[] = '"' . sanitise_string($tag) . '"';
		}
	
		$tags_in = implode(',', $sanitised_tags);
	
		$params['wheres'][] = "(md.name IN ($tags_in) AND md.value = '$query' AND $access)";
	
		$params['count'] = true;
		$count = elgg_get_entities($params);
	
		// no need to continue if nothing here.
		if (!$count) {
			return [
				'entities' => [],
				'count' => $count,
			];
		}
		
		$params['count'] = false;
		if (isset($params['sort']) || !isset($params['order_by'])) {
			$params['order_by'] = self::getOrderBySQL('e', null, $params['sort'], $params['order']);
		}
	
		$entities = elgg_get_entities($params);
		foreach ($entities as $entity) {
			$entity->setVolatileData('search_tag_names', $search_tag_names);
		}
		
		return [
			'entities' => $entities,
			'count' => $count,
		];
	}
	
	/**
	 * Register tags as a custom search type.
	 *
	 * @param string $hook   Hook name
	 * @param string $type   Hook type
	 * @param array  $value  Array of custom search types
	 * @param array  $params Search parameters
	 *
	 * @return array
	 */
	public static function customTypeTags($hook, $type, $value, $params) {
		$value[] = 'tags';
		return $value;
	}
}