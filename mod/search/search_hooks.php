<?php
/**
 * Elgg core search.
 *
 * @package    Elgg
 * @subpackage Search
 */

/**
 * Get objects that match the search parameters.
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param array  $value  Empty array
 * @param array  $params Search parameters
 *
 * @return array
 */
function search_objects_hook($hook, $type, $value, $params) {

	$params['joins'] = (array) elgg_extract('joins', $params, []);
	$params['wheres'] = (array) elgg_extract('wheres', $params, []);

	$query = sanitise_string($params['query']);
	$query_parts = explode(' ', $query);

	$db_prefix = elgg_get_config('dbprefix');

	$params['joins'][] = "JOIN {$db_prefix}metadata md ON e.guid = md.entity_guid";

	$fields = ['title', 'description'];
	$wheres = [];
	foreach ($fields as $field) {
		$sublikes = [];
		foreach ($query_parts as $query_part) {
			$query_part = sanitise_string($query_part);
			if (strlen($query_part) == 0) {
				continue;
			}
			$sublikes[] = "(md.value LIKE '%{$query_part}%')";
		}

		if (empty($sublikes)) {
			continue;
		}

		$wheres[] = "(md.name = '{$field}' AND (" . implode(' AND ', $sublikes) . "))";
	}

	if (!empty($wheres)) {
		$params['wheres'][] = '(' . implode(' OR ', $wheres) . ')';
	}

	$params['count'] = true;
	$count = elgg_get_entities($params);

	// no need to continue if nothing here.
	if (!$count) {
		return ['entities' => [], 'count' => $count];
	}

	$params['count'] = false;
	if (isset($params['sort']) || !isset($params['order_by'])) {
		$params['order_by'] = search_get_order_by_sql('e', 'oe', $params['sort'], $params['order']);
	}
	$params['preload_owners'] = true;
	$entities = elgg_get_entities($params);

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
		$title = search_get_highlighted_relevant_substrings($entity->getDisplayName(), $params['query']);
		$entity->setVolatileData('search_matched_title', $title);

		$desc = search_get_highlighted_relevant_substrings($entity->description, $params['query']);
		$entity->setVolatileData('search_matched_description', $desc);
	}

	return [
		'entities' => $entities,
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
 *
 * @return array
 */
function search_groups_hook($hook, $type, $value, $params) {

	$params['joins'] = (array) elgg_extract('joins', $params, []);
	$params['wheres'] = (array) elgg_extract('wheres', $params, []);

	$query = sanitise_string($params['query']);
	$query_parts = explode(' ', $query);

	$db_prefix = elgg_get_config('dbprefix');

	$params['joins'][] = "JOIN {$db_prefix}metadata md ON e.guid = md.entity_guid";

	$fields = ['name', 'description'];
	$wheres = [];
	foreach ($fields as $field) {
		$sublikes = [];
		foreach ($query_parts as $query_part) {
			$query_part = sanitise_string($query_part);
			if (strlen($query_part) == 0) {
				continue;
			}
			$sublikes[] = "(md.value LIKE '%{$query_part}%')";
		}

		if (empty($sublikes)) {
			continue;
		}

		$wheres[] = "(md.name = '{$field}' AND (" . implode(' AND ', $sublikes) . "))";
	}

	if (!empty($wheres)) {
		$params['wheres'][] = '(' . implode(' OR ', $wheres) . ')';
	}

	$params['count'] = true;

	$count = elgg_get_entities($params);

	// no need to continue if nothing here.
	if (!$count) {
		return ['entities' => [], 'count' => $count];
	}

	$params['count'] = false;
	if (isset($params['sort']) || !isset($params['order_by'])) {
		$params['order_by'] = search_get_order_by_sql('e', '', $params['sort'], $params['order']);
	}
	$entities = elgg_get_entities($params);

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
		$name = search_get_highlighted_relevant_substrings($entity->getDisplayName(), $query);
		$entity->setVolatileData('search_matched_title', $name);

		$description = search_get_highlighted_relevant_substrings($entity->description, $query);
		$entity->setVolatileData('search_matched_description', $description);
	}

	return [
		'entities' => $entities,
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
 *
 * @return array
 */
function search_users_hook($hook, $type, $value, $params) {

	$params['joins'] = (array) elgg_extract('joins', $params, []);
	$params['wheres'] = (array) elgg_extract('wheres', $params, []);

	$query = $params['query'];
	$query_parts = array_filter(explode(' ', $query));

	$metadata_fields = ['username', 'name'];
	$profile_fields = array_keys(elgg_get_config('profile_fields'));

	$params['wheres'][] = function (\Elgg\Database\QueryBuilder $qb, $alias) use ($query_parts, $profile_fields, $metadata_fields) {
		$wheres = [];

		$subclauses = [];
		$md_alias = $qb->joinMetadataTable($alias, 'guid', $metadata_fields, 'left');
		foreach ($query_parts as $part) {
			$where = new \Elgg\Database\Clauses\MetadataWhereClause();
			$where->values = "%{$part}%";
			$where->comparison = 'LIKE';
			$where->value_type = ELGG_VALUE_STRING;
			$where->case_sensitive = false;

			$subclauses[] = $where->prepare($qb, $md_alias);
		}

		$wheres[] = $qb->merge($subclauses, 'AND');

		if (!empty($profile_fields)) {
			$subclauses = [];
			$an_alias = $qb->joinAnnotationTable($alias, 'guid', $profile_fields, 'left');
			foreach ($query_parts as $part) {
				$where = new \Elgg\Database\Clauses\AnnotationWhereClause();
				$where->values = "%{$part}%";
				$where->comparison = 'LIKE';
				$where->value_type = ELGG_VALUE_STRING;
				$where->case_sensitive = false;

				$subclauses[] = $where->prepare($qb, $an_alias);
			}

			$wheres[] = $qb->merge($subclauses, 'AND');
		}

		return $qb->merge($wheres, 'OR');
	};

	$params['count'] = true;
	$count = elgg_get_entities($params);

	// no need to continue if nothing here.
	if (!$count) {
		return ['entities' => [], 'count' => $count];
	}

	$params['count'] = false;
	if (isset($params['sort']) || !isset($params['order_by'])) {
		$params['order_by'] = search_get_order_by_sql('e', 'ue', $params['sort'], $params['order']);
	}
	$entities = elgg_get_entities($params);
	/* @var ElggUser[] $entities */

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
		$title = search_get_highlighted_relevant_substrings($entity->getDisplayName(), $query);

		// include the username if it matches but the display name doesn't.
		if (false !== strpos($entity->username, $query)) {
			$username = search_get_highlighted_relevant_substrings($entity->username, $query);
			$title .= " ($username)";
		}

		$entity->setVolatileData('search_matched_title', $title);

		if (!empty($profile_fields)) {
			$matched = '';
			foreach ($profile_fields as $shortname) {
				$annotations = $entity->getAnnotations([
					'annotation_names' => "profile:$shortname",
					'limit' => false,
				]);
				$values = array_map(function (ElggAnnotation $a) {
					return $a->value;
				}, $annotations);
				foreach ($values as $text) {
					if (stristr($text, $query)) {
						$matched .= elgg_echo("profile:{$shortname}") . ': '
							. search_get_highlighted_relevant_substrings($text, $query);
					}
				}
			}

			$entity->setVolatileData('search_matched_description', $matched);
		}
	}

	return [
		'entities' => $entities,
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
 *
 * @return array
 */
function search_tags_hook($hook, $type, $value, $params) {

	$params['joins'] = (array) elgg_extract('joins', $params, []);
	$params['wheres'] = (array) elgg_extract('wheres', $params, []);

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
			$search_tag_names = [$tag_names];
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
		return ['entities' => [], 'count' => $count];
	}

	// don't use elgg_get_entities_from_metadata() here because of
	// performance issues.  since we don't care what matches at this point
	// use an IN clause to grab everything that matches at once and sort
	// out the matches later.
	$params['joins'][] = "JOIN {$db_prefix}metadata md on e.guid = md.entity_guid";

	$access = _elgg_get_access_where_sql([
		'table_alias' => 'md',
		'guid_column' => 'entity_guid',
	]);
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
		return ['entities' => [], 'count' => $count];
	}

	$params['count'] = false;
	if (isset($params['sort']) || !isset($params['order_by'])) {
		$params['order_by'] = search_get_order_by_sql('e', null, $params['sort'], $params['order']);
	}
	$entities = elgg_get_entities($params);

	// add the volatile data for why these entities have been returned.
	foreach ($entities as $entity) {
		$matched_tags_strs = [];

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

		$title_str = elgg_get_excerpt($entity->getDisplayName(), 300);
		$desc_str = elgg_get_excerpt($entity->description, 300);

		$tags_str = implode('. ', $matched_tags_strs);
		$tags_str = search_get_highlighted_relevant_substrings($tags_str, $params['query'], 30, 300, true);

		$entity->setVolatileData('search_matched_title', $title_str);
		$entity->setVolatileData('search_matched_description', $desc_str);
		$entity->setVolatileData('search_matched_extra', $tags_str);
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
function search_custom_types_tags_hook($hook, $type, $value, $params) {
	$value[] = 'tags';

	return $value;
}
