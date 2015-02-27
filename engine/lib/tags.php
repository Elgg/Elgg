<?php
/**
 * Elgg tags
 * Functions for managing tags and tag clouds.
 *
 * @package Elgg.Core
 * @subpackage Tags
 */

/**
 * Takes in a comma-separated string and returns an array of tags
 * which have been trimmed
 *
 * @param string $string Comma-separated tag string
 *
 * @return mixed An array of strings or the original data if input was not a string
 */
function string_to_tag_array($string) {
	if (!is_string($string)) {
		return $string;
	}
	
	$ar = explode(",", $string);
	$ar = array_map('trim', $ar);
	$ar = array_filter($ar, 'is_not_null');
	$ar = array_map('strip_tags', $ar);
	return $ar;	
}

/**
 * Get popular tags and their frequencies
 *
 * Supports similar arguments as elgg_get_entities()
 *
 * @param array $options Array in format:
 *
 * 	threshold => INT minimum tag count
 *
 * 	tag_names => array() metadata tag names - must be registered tags
 *
 * 	limit => INT number of tags to return (default from settings)
 *
 *  types => null|STR entity type (SQL: type = '$type')
 *
 * 	subtypes => null|STR entity subtype (SQL: subtype = '$subtype')
 *
 * 	type_subtype_pairs => null|ARR (array('type' => 'subtype'))
 *  (SQL: type = '$type' AND subtype = '$subtype') pairs
 *
 * 	owner_guids => null|INT entity guid
 *
 * 	container_guids => null|INT container_guid
 *
 * 	site_guids => null (current_site)|INT site_guid
 *
 * 	created_time_lower => null|INT Created time lower boundary in epoch time
 *
 * 	created_time_upper => null|INT Created time upper boundary in epoch time
 *
 * 	modified_time_lower => null|INT Modified time lower boundary in epoch time
 *
 * 	modified_time_upper => null|INT Modified time upper boundary in epoch time
 *
 * 	wheres => array() Additional where clauses to AND together
 *
 * 	joins => array() Additional joins
 *
 * @return 	object[]|false If no tags or error, false
 * 						   otherwise, array of objects with ->tag and ->total values
 * @since 1.7.1
 */
function elgg_get_tags(array $options = array()) {
	global $CONFIG;

	$defaults = array(
		'threshold' => 1,
		'tag_names' => array(),
		'limit' => elgg_get_config('default_limit'),

		'types' => ELGG_ENTITIES_ANY_VALUE,
		'subtypes' => ELGG_ENTITIES_ANY_VALUE,
		'type_subtype_pairs' => ELGG_ENTITIES_ANY_VALUE,

		'owner_guids' => ELGG_ENTITIES_ANY_VALUE,
		'container_guids' => ELGG_ENTITIES_ANY_VALUE,
		'site_guids' => $CONFIG->site_guid,

		'modified_time_lower' => ELGG_ENTITIES_ANY_VALUE,
		'modified_time_upper' => ELGG_ENTITIES_ANY_VALUE,
		'created_time_lower' => ELGG_ENTITIES_ANY_VALUE,
		'created_time_upper' => ELGG_ENTITIES_ANY_VALUE,

		'joins' => array(),
		'wheres' => array(),
	);


	$options = array_merge($defaults, $options);

	$singulars = array('type', 'subtype', 'owner_guid', 'container_guid', 'site_guid', 'tag_name');
	$options = _elgg_normalize_plural_options_array($options, $singulars);

	$registered_tags = elgg_get_registered_tag_metadata_names();

	if (!is_array($options['tag_names'])) {
		return false;
	}

	// empty array so use all registered tag names
	if (count($options['tag_names']) == 0) {
		$options['tag_names'] = $registered_tags;
	}

	$diff = array_diff($options['tag_names'], $registered_tags);
	if (count($diff) > 0) {
		elgg_deprecated_notice('Tag metadata names must be registered by elgg_register_tag_metadata_name()', 1.7);
		// return false;
	}


	$wheres = $options['wheres'];

	// catch for tags that were spaces
	$wheres[] = "msv.string != ''";

	$sanitised_tags = array();
	foreach ($options['tag_names'] as $tag) {
		$sanitised_tags[] = '"' . sanitise_string($tag) . '"';
	}
	$tags_in = implode(',', $sanitised_tags);
	$wheres[] = "(msn.string IN ($tags_in))";

	$wheres[] = _elgg_get_entity_type_subtype_where_sql('e', $options['types'],
		$options['subtypes'], $options['type_subtype_pairs']);
	$wheres[] = _elgg_get_guid_based_where_sql('e.site_guid', $options['site_guids']);
	$wheres[] = _elgg_get_guid_based_where_sql('e.owner_guid', $options['owner_guids']);
	$wheres[] = _elgg_get_guid_based_where_sql('e.container_guid', $options['container_guids']);
	$wheres[] = _elgg_get_entity_time_where_sql('e', $options['created_time_upper'],
		$options['created_time_lower'], $options['modified_time_upper'], $options['modified_time_lower']);

	// see if any functions failed
	// remove empty strings on successful functions
	foreach ($wheres as $i => $where) {
		if ($where === false) {
			return false;
		} elseif (empty($where)) {
			unset($wheres[$i]);
		}
	}

	// remove identical where clauses
	$wheres = array_unique($wheres);

	$joins = $options['joins'];

	$joins[] = "JOIN {$CONFIG->dbprefix}metadata md on md.entity_guid = e.guid";
	$joins[] = "JOIN {$CONFIG->dbprefix}metastrings msv on msv.id = md.value_id";
	$joins[] = "JOIN {$CONFIG->dbprefix}metastrings msn on md.name_id = msn.id";

	// remove identical join clauses
	$joins = array_unique($joins);

	foreach ($joins as $i => $join) {
		if ($join === false) {
			return false;
		} elseif (empty($join)) {
			unset($joins[$i]);
		}
	}


	$query  = "SELECT msv.string as tag, count(msv.id) as total ";
	$query .= "FROM {$CONFIG->dbprefix}entities e ";

	// add joins
	foreach ($joins as $j) {
		$query .= " $j ";
	}

	// add wheres
	$query .= ' WHERE ';

	foreach ($wheres as $w) {
		$query .= " $w AND ";
	}

	// Add access controls
	$query .= _elgg_get_access_where_sql();

	$threshold = sanitise_int($options['threshold']);
	$query .= " GROUP BY msv.string HAVING total >= {$threshold} ";
	$query .= " ORDER BY total DESC ";

	$limit = sanitise_int($options['limit']);
	$query .= " LIMIT {$limit} ";

	return get_data($query);
}

/**
 * Registers a metadata name as containing tags for an entity.
 * This is required if you are using a non-standard metadata name
 * for your tags.
 *
 * @param string $name Tag name
 *
 * @return bool
 * @since 1.7.0
 */
function elgg_register_tag_metadata_name($name) {
	global $CONFIG;

	if (!isset($CONFIG->registered_tag_metadata_names)) {
		$CONFIG->registered_tag_metadata_names = array();
	}

	if (!in_array($name, $CONFIG->registered_tag_metadata_names)) {
		$CONFIG->registered_tag_metadata_names[] = $name;
	}

	return true;
}

/**
 * Returns an array of valid metadata names for tags.
 *
 * @return array
 * @since 1.7.0
 */
function elgg_get_registered_tag_metadata_names() {
	global $CONFIG;

	$names = (isset($CONFIG->registered_tag_metadata_names)) ? $CONFIG->registered_tag_metadata_names : array();

	return $names;
}

/**
 * @access private
 */
function _elgg_tags_init() {
	// register the standard tags metadata name
	elgg_register_tag_metadata_name('tags');
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_tags_init');
};
