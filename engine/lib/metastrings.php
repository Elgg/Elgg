<?php
/**
 * Elgg metastrngs
 * Functions to manage object metastrings.
 *
 * @package Elgg.Core
 * @subpackage DataModel.MetaStrings
 */

/** Cache metastrings for a page */
global $METASTRINGS_CACHE;
$METASTRINGS_CACHE = array();

/** Keep a record of strings we know don't exist */
global $METASTRINGS_DEADNAME_CACHE;
$METASTRINGS_DEADNAME_CACHE = array();



/**
 * Return the meta string id for a given tag, or false.
 *
 * @param string $string         The value to store
 * @param bool   $case_sensitive Do we want to make the query case sensitive?
 *                               If not there may be more than one result
 *
 * @return int|array|false meta   string id, array of ids or false if none found
 */
function get_metastring_id($string, $case_sensitive = TRUE) {
	global $CONFIG, $METASTRINGS_CACHE, $METASTRINGS_DEADNAME_CACHE;

	$string = sanitise_string($string);

	// caching doesn't work for case insensitive searches
	if ($case_sensitive) {
		$result = array_search($string, $METASTRINGS_CACHE, true);

		if ($result !== false) {
			elgg_log("** Returning id for string:$string from cache.");
			return $result;
		}

		// See if we have previously looked for this and found nothing
		if (in_array($string, $METASTRINGS_DEADNAME_CACHE, true)) {
			return false;
		}

		// Experimental memcache
		$msfc = null;
		static $metastrings_memcache;
		if ((!$metastrings_memcache) && (is_memcache_available())) {
			$metastrings_memcache = new ElggMemcache('metastrings_memcache');
		}
		if ($metastrings_memcache) {
			$msfc = $metastrings_memcache->load($string);
		}
		if ($msfc) {
			return $msfc;
		}
	}

	// Case sensitive
	if ($case_sensitive) {
		$query = "SELECT * from {$CONFIG->dbprefix}metastrings where string= BINARY '$string' limit 1";
	} else {
		$query = "SELECT * from {$CONFIG->dbprefix}metastrings where string = '$string'";
	}

	$row = FALSE;
	$metaStrings = get_data($query, "entity_row_to_elggstar");
	if (is_array($metaStrings)) {
		if (sizeof($metaStrings) > 1) {
			$ids = array();
			foreach ($metaStrings as $metaString) {
				$ids[] = $metaString->id;
			}
			return $ids;
		} else if (isset($metaStrings[0])) {
			$row = $metaStrings[0];
		}
	}

	if ($row) {
		$METASTRINGS_CACHE[$row->id] = $row->string; // Cache it

		// Attempt to memcache it if memcache is available
		if ($metastrings_memcache) {
			$metastrings_memcache->save($row->string, $row->id);
		}

		elgg_log("** Cacheing string '{$row->string}'");

		return $row->id;
	} else {
		$METASTRINGS_DEADNAME_CACHE[$string] = $string;
	}

	return false;
}

/**
 * When given an ID, returns the corresponding metastring
 *
 * @param int $id Metastring ID
 *
 * @return string Metastring
 */
function get_metastring($id) {
	global $CONFIG, $METASTRINGS_CACHE;

	$id = (int) $id;

	if (isset($METASTRINGS_CACHE[$id])) {
		elgg_log("** Returning string for id:$id from cache.");

		return $METASTRINGS_CACHE[$id];
	}

	$row = get_data_row("SELECT * from {$CONFIG->dbprefix}metastrings where id='$id' limit 1");
	if ($row) {
		$METASTRINGS_CACHE[$id] = $row->string; // Cache it
		elgg_log("** Cacheing string '{$row->string}'");

		return $row->string;
	}

	return false;
}

/**
 * Add a metastring.
 * It returns the id of the tag, whether by creating it or updating it.
 *
 * @param string $string         The value (whatever that is) to be stored
 * @param bool   $case_sensitive Do we want to make the query case sensitive?
 *
 * @return mixed Integer tag or false.
 */
function add_metastring($string, $case_sensitive = true) {
	global $CONFIG, $METASTRINGS_CACHE, $METASTRINGS_DEADNAME_CACHE;

	$sanstring = sanitise_string($string);

	$id = get_metastring_id($string, $case_sensitive);
	if ($id) {
		return $id;
	}

	$result = insert_data("INSERT into {$CONFIG->dbprefix}metastrings (string) values ('$sanstring')");
	if ($result) {
		$METASTRINGS_CACHE[$result] = $string;
		if (isset($METASTRINGS_DEADNAME_CACHE[$string])) {
			unset($METASTRINGS_DEADNAME_CACHE[$string]);
		}
	}

	return $result;
}

/**
 * Delete any orphaned entries in metastrings. This is run by the garbage collector.
 *
 * @return bool
 */
function delete_orphaned_metastrings() {
	global $CONFIG;

	// If memcache is enabled then we need to flush it of deleted values
	if (is_memcache_available()) {
		$select_query = "
		SELECT *
		from {$CONFIG->dbprefix}metastrings where
		(
			(id not in (select name_id from {$CONFIG->dbprefix}metadata)) AND
			(id not in (select value_id from {$CONFIG->dbprefix}metadata)) AND
			(id not in (select name_id from {$CONFIG->dbprefix}annotations)) AND
			(id not in (select value_id from {$CONFIG->dbprefix}annotations))
		)";

		$dead = get_data($select_query);
		if ($dead) {
			static $metastrings_memcache;
			if (!$metastrings_memcache) {
				$metastrings_memcache = new ElggMemcache('metastrings_memcache');
			}

			foreach ($dead as $d) {
				$metastrings_memcache->delete($d->string);
			}
		}
	}

	$query = "
		DELETE
		from {$CONFIG->dbprefix}metastrings where
		(
			(id not in (select name_id from {$CONFIG->dbprefix}metadata)) AND
			(id not in (select value_id from {$CONFIG->dbprefix}metadata)) AND
			(id not in (select name_id from {$CONFIG->dbprefix}annotations)) AND
			(id not in (select value_id from {$CONFIG->dbprefix}annotations))
		)";

	return delete_data($query);
}

/**
 * Returns an array of either ElggAnnotation or ElggMetadata objects.
 * Accepts all elgg_get_entities() options for entity restraints.
 *
 * @see elgg_get_entities
 *
 * @param array $options Array in format:
 *
 * 	metastring_names              => NULL|ARR metastring names
 *
 * 	metastring_values             => NULL|ARR metastring values
 *
 * 	metastring_ids                => NULL|ARR metastring ids
 *
 * 	metastring_case_sensitive     => BOOL     Overall Case sensitive
 *
 *  metastring_owner_guids        => NULL|ARR Guids for metadata owners
 *
 *  metastring_created_time_lower => INT      Lower limit for created time.
 *
 *  metastring_created_time_upper => INT      Upper limit for created time.
 *
 *  metastring_calculation        => STR      Perform the MySQL function on the metastring values
 *                                            returned.
 *                                            This differs from egef_annotation_calculation in that
 *                                            it returns only the calculation of all annotation values.
 *                                            You can sum, avg, count, etc. egef_annotation_calculation()
 *                                            returns ElggEntities ordered by a calculation on their
 *                                            annotation values.
 *
 *  metastring_type               => STR      metadata or annotation(s)
 *
 * @return mixed
 * @access private
 */
function elgg_get_metastring_based_objects($options) {
	$options = elgg_normalize_metastrings_options($options);

	switch ($options['metastring_type']) {
		case 'metadata':
			$type = 'metadata';
			$callback = 'row_to_elggmetadata';
			break;

		case 'annotations':
		case 'annotation':
			$type = 'annotations';
			$callback = 'row_to_elggannotation';
			break;

		default:
			return false;
	}

	$defaults = array(
		// entities
		'types'					=>	ELGG_ENTITIES_ANY_VALUE,
		'subtypes'				=>	ELGG_ENTITIES_ANY_VALUE,
		'type_subtype_pairs'	=>	ELGG_ENTITIES_ANY_VALUE,

		'guids'					=>	ELGG_ENTITIES_ANY_VALUE,
		'owner_guids'			=>	ELGG_ENTITIES_ANY_VALUE,
		'container_guids'		=>	ELGG_ENTITIES_ANY_VALUE,
		'site_guids'			=>	get_config('site_guid'),

		'modified_time_lower'	=>	ELGG_ENTITIES_ANY_VALUE,
		'modified_time_upper'	=>	ELGG_ENTITIES_ANY_VALUE,
		'created_time_lower'	=>	ELGG_ENTITIES_ANY_VALUE,
		'created_time_upper'	=>	ELGG_ENTITIES_ANY_VALUE,

		// options are normalized to the plural in case we ever add support for them.
		'metastring_names'							=>	ELGG_ENTITIES_ANY_VALUE,
		'metastring_values'							=>	ELGG_ENTITIES_ANY_VALUE,
		//'metastring_name_value_pairs'				=>	ELGG_ENTITIES_ANY_VALUE,
		//'metastring_name_value_pairs_operator'	=>	'AND',

		'metastring_case_sensitive' 				=>	TRUE,
		//'order_by_metastring'						=>	array(),
		'metastring_calculation'					=>	ELGG_ENTITIES_NO_VALUE,

		'metastring_created_time_lower'				=>	ELGG_ENTITIES_ANY_VALUE,
		'metastring_created_time_upper'				=>	ELGG_ENTITIES_ANY_VALUE,

		'metastring_owner_guids'					=>	ELGG_ENTITIES_ANY_VALUE,

		'metastring_ids'							=>	ELGG_ENTITIES_ANY_VALUE,

		// sql
		'order_by'	=>	'n_table.time_created asc',
		'limit'		=>	10,
		'offset'	=>	0,
		'count'		=>	FALSE,
		'selects'	=>	array(),
		'wheres'	=>	array(),
		'joins'		=>	array(),

		'callback'	=> $callback
	);

	// @todo Ignore site_guid right now because of #2910
	$options['site_guid'] = ELGG_ENTITIES_ANY_VALUE;

	$options = array_merge($defaults, $options);

	// can't use helper function with type_subtype_pair because
	// it's already an array...just need to merge it
	if (isset($options['type_subtype_pair'])) {
		if (isset($options['type_subtype_pairs'])) {
			$options['type_subtype_pairs'] = array_merge($options['type_subtype_pairs'],
				$options['type_subtype_pair']);
		} else {
			$options['type_subtype_pairs'] = $options['type_subtype_pair'];
		}
	}

	$singulars = array(
		'type', 'subtype', 'type_subtype_pair',
		'guid', 'owner_guid', 'container_guid', 'site_guid',
		'metastring_name', 'metastring_value',
		'metastring_owner_guid', 'metastring_id',
		'select', 'where', 'join'
	);

	$options = elgg_normalise_plural_options_array($options, $singulars);

	if (!$options) {
		return false;
	}

	$db_prefix = elgg_get_config('dbprefix');

	// evaluate where clauses
	if (!is_array($options['wheres'])) {
		$options['wheres'] = array($options['wheres']);
	}

	$wheres = $options['wheres'];

	// entities
	$wheres[] = elgg_get_entity_type_subtype_where_sql('e', $options['types'],
		$options['subtypes'], $options['type_subtype_pairs']);

	$wheres[] = elgg_get_guid_based_where_sql('e.guid', $options['guids']);
	$wheres[] = elgg_get_guid_based_where_sql('e.owner_guid', $options['owner_guids']);
	$wheres[] = elgg_get_guid_based_where_sql('e.container_guid', $options['container_guids']);
	$wheres[] = elgg_get_guid_based_where_sql('e.site_guid', $options['site_guids']);

	$wheres[] = elgg_get_entity_time_where_sql('e', $options['created_time_upper'],
		$options['created_time_lower'], $options['modified_time_upper'], $options['modified_time_lower']);


	$wheres[] = elgg_get_entity_time_where_sql('n_table', $options['metastring_created_time_upper'],
		$options['metastring_created_time_lower'], null, null);

	$wheres[] = elgg_get_guid_based_where_sql('n_table.owner_guid',
		$options['metastring_owner_guids']);

	// remove identical where clauses
	$wheres = array_unique($wheres);

	// see if any functions failed
	// remove empty strings on successful functions
	foreach ($wheres as $i => $where) {
		if ($where === FALSE) {
			return FALSE;
		} elseif (empty($where)) {
			unset($wheres[$i]);
		}
	}

	// evaluate join clauses
	if (!is_array($options['joins'])) {
		$options['joins'] = array($options['joins']);
	}

	$joins = $options['joins'];
	$joins[] = "JOIN {$db_prefix}entities e ON n_table.entity_guid = e.guid";

	// evaluate selects
	if (!is_array($options['selects'])) {
		$options['selects'] = array($options['selects']);
	}

	$selects = $options['selects'];

	// allow count shortcut
	if ($options['count']) {
		$options['metastring_calculation'] = 'count';
	}

	// For performance reasons we don't want the joins required for metadata / annotations
	// unless we're going through one of their callbacks.
	// this means we expect the functions passing different callbacks to pass their required joins.
	// If we're doing a calculation
	$custom_callback = ($options['callback'] == 'row_to_elggmetadata'
						|| $options['callback'] == 'row_to_elggannotation');
	$is_calculation = $options['metastring_calculation'] ? true : false;
	
	if ($custom_callback || $is_calculation) {
		$joins[] = "JOIN {$db_prefix}metastrings n on n_table.name_id = n.id";
		$joins[] = "JOIN {$db_prefix}metastrings v on n_table.value_id = v.id";

		$selects[] = 'n.string as name';
		$selects[] = 'v.string as value';
	}

	foreach ($joins as $i => $join) {
		if ($join === FALSE) {
			return FALSE;
		} elseif (empty($join)) {
			unset($joins[$i]);
		}
	}

	// metastrings
	$metastring_clauses = elgg_get_metastring_sql('n_table', $options['metastring_names'],
		$options['metastring_values'], null, $options['metastring_ids'],
		$options['metastring_case_sensitive']);

	if ($metastring_clauses) {
		$wheres = array_merge($wheres, $metastring_clauses['wheres']);
		$joins = array_merge($joins, $metastring_clauses['joins']);
	}

	if ($options['metastring_calculation'] === ELGG_ENTITIES_NO_VALUE) {
		$selects = array_unique($selects);
		// evalutate selects
		$select_str = '';
		if ($selects) {
			foreach ($selects as $select) {
				$select_str .= ", $select";
			}
		}

		$query = "SELECT DISTINCT n_table.*{$select_str} FROM {$db_prefix}$type n_table";
	} else {
		$query = "SELECT {$options['metastring_calculation']}(v.string) as calculation FROM {$db_prefix}$type n_table";
	}

	// remove identical join clauses
	$joins = array_unique($joins);

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
	$query .= get_access_sql_suffix('e');

	// reverse order by
	if (isset($options['reverse_order_by']) && $options['reverse_order_by']) {
		$options['order_by'] = elgg_sql_reverse_order_by_clause($options['order_by'],
			$defaults['order_by']);
	}

	if ($options['metastring_calculation'] === ELGG_ENTITIES_NO_VALUE) {
		if (isset($options['group_by'])) {
			$options['group_by'] = sanitise_string($options['group_by']);
			$query .= " GROUP BY {$options['group_by']}";
		}

		if (isset($options['order_by']) && $options['order_by']) {
			$options['order_by'] = sanitise_string($options['order_by']);
			$query .= " ORDER BY {$options['order_by']}, n_table.id";
		}

		if ($options['limit']) {
			$limit = sanitise_int($options['limit']);
			$offset = sanitise_int($options['offset'], false);
			$query .= " LIMIT $offset, $limit";
		}

		$dt = get_data($query, $options['callback']);
		return $dt;
	} else {
		$result = get_data_row($query);
		return $result->calculation;
	}
}

/**
 * Returns an array of joins and wheres for use in metastrings.
 *
 * @note The $pairs is reserved for name/value pairs if we want to implement those.
 *
 * @param string $table          The annotation or metadata table name or alias
 * @param array  $names          An array of names
 * @param array  $values         An array of values
 * @param array  $pairs          Name / value pairs. Not currently used.
 * @param array  $ids            Metastring IDs
 * @param bool   $case_sensitive Should name and values be case sensitive?
 *
 * @return array
 */
function elgg_get_metastring_sql($table, $names = null, $values = null,
	$pairs = null, $ids = null, $case_sensitive = false) {

	if ((!$names && $names !== 0)
		&& (!$values && $values !== 0)
		&& !$ids
		&& (!$pairs && $pairs !== 0)) {

		return '';
	}

	$db_prefix = elgg_get_config('dbprefix');

	// join counter for incremental joins.
	$i = 1;

	// binary forces byte-to-byte comparision of strings, making
	// it case- and diacritical-mark- sensitive.
	// only supported on values.
	$binary = ($case_sensitive) ? ' BINARY ' : '';

	$access = get_access_sql_suffix($table);

	$return = array (
		'joins' => array (),
		'wheres' => array()
	);

	$wheres = array();

	// get names wheres and joins
	$names_where = '';
	if ($names !== NULL) {
		if (!is_array($names)) {
			$names = array($names);
		}

		$sanitised_names = array();
		foreach ($names as $name) {
			// normalise to 0.
			if (!$name) {
				$name = '0';
			}
			$sanitised_names[] = '\'' . sanitise_string($name) . '\'';
		}

		if ($names_str = implode(',', $sanitised_names)) {
			$return['joins'][] = "JOIN {$db_prefix}metastrings msn on $table.name_id = msn.id";
			$names_where = "(msn.string IN ($names_str))";
		}
	}

	// get values wheres and joins
	$values_where = '';
	if ($values !== NULL) {
		if (!is_array($values)) {
			$values = array($values);
		}

		$sanitised_values = array();
		foreach ($values as $value) {
			// normalize to 0
			if (!$value) {
				$value = 0;
			}
			$sanitised_values[] = '\'' . sanitise_string($value) . '\'';
		}

		if ($values_str = implode(',', $sanitised_values)) {
			$return['joins'][] = "JOIN {$db_prefix}metastrings msv on $table.value_id = msv.id";
			$values_where = "({$binary}msv.string IN ($values_str))";
		}
	}

	if ($ids !== NULL) {
		if (!is_array($ids)) {
			$ids = array($ids);
		}

		$ids_str = implode(',', $ids);

		if ($ids_str) {
			$wheres[] = "n_table.id IN ($ids_str)";
		}
	}

	if ($names_where && $values_where) {
		$wheres[] = "($names_where AND $values_where AND $access)";
	} elseif ($names_where) {
		$wheres[] = "($names_where AND $access)";
	} elseif ($values_where) {
		$wheres[] = "($values_where AND $access)";
	}

	if ($where = implode(' AND ', $wheres)) {
		$return['wheres'][] = "($where)";
	}

	return $return;
}

/**
 * Normalizes metadata / annotation option names to their
 * corresponding metastrings name.
 *
 * @param array $options An options array
 * @since 1.8
 * @access private
 * @return array
 */
function elgg_normalize_metastrings_options(array $options = array()) {

	// support either metastrings_type or metastring_type
	// because I've made this mistake many times and hunting it down is a pain...
	$type = elgg_extract('metastring_type', $options, null);
	$type = elgg_extract('metastrings_type', $options, $type);

	$options['metastring_type'] = $type;

	// support annotation_ and annotations_ because they're way too easy to confuse
	$prefixes = array('metadata_', 'annotation_', 'annotations_');

	// map the metadata_* options to metastring_* options
	$map = array(
		'names' 				=>	'metastring_names',
		'values' 				=>	'metastring_values',
		'case_sensitive' 		=>	'metastring_case_sensitive',
		'owner_guids' 			=>	'metastring_owner_guids',
		'created_time_lower'	=>	'metastring_created_time_lower',
		'created_time_upper'	=>	'metastring_created_time_upper',
		'calculation'			=>	'metastring_calculation',
		'ids'					=>	'metastring_ids'
	);

	foreach ($prefixes as $prefix) {
		$singulars = array("{$prefix}name", "{$prefix}value", "{$prefix}owner_guid", "{$prefix}id");
		$options = elgg_normalise_plural_options_array($options, $singulars);

		foreach ($map as $specific => $normalized) {
			$key = $prefix . $specific;
			if (isset($options[$key])) {
				$options[$normalized] = $options[$key];
			}
		}
	}

	return $options;
}

/**
 * Enables or disables a metastrings-based object by its id.
 *
 * @warning To enable disabled metastrings you must first use
 * {@link access_show_hidden_entities()}.
 *
 * @param int    $id      The object's ID
 * @param string $enabled Value to set to: yes or no
 * @param string $type    The type of table to use: metadata or anntations
 *
 * @return bool
 * @since 1.8
 * @access private
 */
function elgg_set_metastring_based_object_enabled_by_id($id, $enabled, $type) {
	$id = (int)$id;
	$db_prefix = elgg_get_config('dbprefix');

	$object = elgg_get_metastring_based_object_from_id($id, $type);

	switch($type) {
		case 'annotation':
		case 'annotations':
			$table = "{$db_prefix}annotations";
			break;

		case 'metadata':
			$table = "{$db_prefix}metadata";
			break;
	}

	if ($enabled === 'yes' || $enabled === 1 || $enabled === true) {
		$enabled = 'yes';
		$event = 'enable';
	} elseif ($enabled === 'no' || $enabled === 0 || $enabled === false) {
		$enabled = 'no';
		$event = 'disable';
	} else {
		return false;
	}

	$return = false;

	if ($object) {
		// don't set it if it's already set.
		if ($object->enabled == $enabled) {
			$return = false;
		} elseif ($object->canEdit() && (elgg_trigger_event($event, $type, $object))) {
			$return = update_data("UPDATE $table SET enabled = '$enabled' where id = $id");
		}
	}

	return $return;
}

/**
 * Runs metastrings-based objects found using $options through $callback
 *
 * @warning Unlike elgg_get_metastring_based_objects() this will not accept an
 * empty options array!
 *
 * @param array  $options  An options array. {@See elgg_get_metastring_based_objects()}
 * @param string $callback The callback to pass each result through
 * @return mixed
 * @access private
 * @since 1.8
 */
function elgg_batch_metastring_based_objects(array $options, $callback) {
	if (!$options || !is_array($options)) {
		return false;
	}

	$batch = new ElggBatch('elgg_get_metastring_based_objects', $options, $callback);
	$r = $batch->callbackResult;

	return $r;
}

/**
 * Returns a singular metastring-based object by its ID.
 *
 * @param int    $id   The metastring-based object's ID
 * @param string $type The type: annotation or metadata
 * @return mixed
 *
 * @since 1.8
 * @access private
 */
function elgg_get_metastring_based_object_from_id($id, $type) {
	$id = (int)$id;
	if (!$id) {
		return false;
	}

	$options = array(
		'metastring_type' => $type,
		'metastring_id' => $id
	);

	$obj = elgg_get_metastring_based_objects($options);

	if ($obj && count($obj) == 1) {
		return $obj[0];
	}

	return false;
}

/**
 * Deletes a metastring-based object by its id
 *
 * @param int    $id   The object's ID
 * @param string $type The object's metastring type: annotation or metadata
 * @return bool
 *
 * @since 1.8
 * @access private
 */
function elgg_delete_metastring_based_object_by_id($id, $type) {
	$id = (int)$id;
	$db_prefix = elgg_get_config('dbprefix');

	switch ($type) {
		case 'annotation':
		case 'annotations':
			$type = 'annotations';
			break;

		case 'metadata':
			$type = 'metadata';
			break;

		default:
			return false;
	}

	$obj = elgg_get_metastring_based_object_from_id($id, $type);
	$table = $db_prefix . $type;

	if ($obj) {
		// Tidy up if memcache is enabled.
		// @todo only metadata is supported
		if ($type == 'metadata') {
			static $metabyname_memcache;
			if ((!$metabyname_memcache) && (is_memcache_available())) {
				$metabyname_memcache = new ElggMemcache('metabyname_memcache');
			}

			if ($metabyname_memcache) {
				$metabyname_memcache->delete("{$obj->entity_guid}:{$obj->name_id}");
			}
		}

		if (($obj->canEdit()) && (elgg_trigger_event('delete', $type, $obj))) {
			return delete_data("DELETE from $table where id=$id");
		}
	}

	return false;
}

/**
 * Entities interface helpers
 */

/**
 * Returns options to pass to elgg_get_entities() for metastrings operations.
 *
 * @param string $type    Metastring type: annotations or metadata
 * @param array  $options Options
 *
 * @return array
 * @since 1.7.0
 */
function elgg_entities_get_metastrings_options($type, $options) {
	$valid_types = array('metadata', 'annotation');
	if (!in_array($type, $valid_types)) {
		return FALSE;
	}

	// the options for annotations are singular (annotation_name) but the table
	// is plural (elgg_annotations) so rewrite for the table name.
	$n_table = ($type == 'annotation') ? 'annotations' : $type;

	$singulars = array("{$type}_name", "{$type}_value",
		"{$type}_name_value_pair", "{$type}_owner_guid");
	$options = elgg_normalise_plural_options_array($options, $singulars);

	$clauses = elgg_get_entity_metadata_where_sql('e', $n_table, $options["{$type}_names"],
		$options["{$type}_values"], $options["{$type}_name_value_pairs"],
		$options["{$type}_name_value_pairs_operator"], $options["{$type}_case_sensitive"],
		$options["order_by_{$type}"], $options["{$type}_owner_guids"]);

	if ($clauses) {
		// merge wheres to pass to get_entities()
		if (isset($options['wheres']) && !is_array($options['wheres'])) {
			$options['wheres'] = array($options['wheres']);
		} elseif (!isset($options['wheres'])) {
			$options['wheres'] = array();
		}

		$options['wheres'] = array_merge($options['wheres'], $clauses['wheres']);

		// merge joins to pass to get_entities()
		if (isset($options['joins']) && !is_array($options['joins'])) {
			$options['joins'] = array($options['joins']);
		} elseif (!isset($options['joins'])) {
			$options['joins'] = array();
		}

		$options['joins'] = array_merge($options['joins'], $clauses['joins']);

		if ($clauses['orders']) {
			$order_by_metadata = implode(", ", $clauses['orders']);
			if (isset($options['order_by']) && $options['order_by']) {
				$options['order_by'] = "$order_by_metadata, {$options['order_by']}";
			} else {
				$options['order_by'] = "$order_by_metadata, e.time_created DESC";
			}
		}
	}

	return $options;
}

// unit testing
elgg_register_plugin_hook_handler('unit_test', 'system', 'metastrings_test');

/**
 * Metadata unit test
 *
 * @param string $hook   unit_test
 * @param string $type   system
 * @param mixed  $value  Array of other tests
 * @param mixed  $params Params
 *
 * @return array
 */
function metastrings_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = $CONFIG->path . 'engine/tests/api/metastrings.php';
	return $value;
}
