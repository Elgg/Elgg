<?php
/**
 * Elgg metastrngs
 * Functions to manage object metastrings.
 *
 * @package Elgg.Core
 * @subpackage DataModel.MetaStrings
 */

/**
 * Returns an array of either \ElggAnnotation or \ElggMetadata objects.
 * Accepts all elgg_get_entities() options for entity restraints.
 *
 * @see elgg_get_entities
 *
 * @param array $options Array in format:
 *
 * 	metastring_names              => null|ARR metastring names
 *
 * 	metastring_values             => null|ARR metastring values
 *
 * 	metastring_ids                => null|ARR metastring ids
 *
 * 	metastring_case_sensitive     => BOOL     Overall Case sensitive
 *
 *  metastring_owner_guids        => null|ARR Guids for metadata owners
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
 *                                            returns \ElggEntities ordered by a calculation on their
 *                                            annotation values.
 *
 *  metastring_type               => STR      metadata or annotation(s)
 *
 * @return \ElggExtender[]|int An array or count of metastring based objects
 * @access private
 */
function _elgg_get_metastring_based_objects($options) {
	$options = _elgg_normalize_metastrings_options($options);

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

	$defaults = [
		// entities
		'types' => ELGG_ENTITIES_ANY_VALUE,
		'subtypes' => ELGG_ENTITIES_ANY_VALUE,
		'type_subtype_pairs' => ELGG_ENTITIES_ANY_VALUE,

		'guids' => ELGG_ENTITIES_ANY_VALUE,
		'owner_guids' => ELGG_ENTITIES_ANY_VALUE,
		'container_guids' => ELGG_ENTITIES_ANY_VALUE,

		'modified_time_lower' => ELGG_ENTITIES_ANY_VALUE,
		'modified_time_upper' => ELGG_ENTITIES_ANY_VALUE,
		'created_time_lower' => ELGG_ENTITIES_ANY_VALUE,
		'created_time_upper' => ELGG_ENTITIES_ANY_VALUE,

		// options are normalized to the plural in case we ever add support for them.
		'metastring_names' => ELGG_ENTITIES_ANY_VALUE,
		'metastring_values' => ELGG_ENTITIES_ANY_VALUE,

		'metastring_case_sensitive' => true,
		'metastring_calculation' => ELGG_ENTITIES_NO_VALUE,

		'metastring_created_time_lower' => ELGG_ENTITIES_ANY_VALUE,
		'metastring_created_time_upper' => ELGG_ENTITIES_ANY_VALUE,

		'metastring_owner_guids' => ELGG_ENTITIES_ANY_VALUE,
		
		'metastring_ids' => ELGG_ENTITIES_ANY_VALUE,

		// sql
		'order_by' => 'n_table.time_created ASC, n_table.id ASC',
		'limit' => elgg_get_config('default_limit'),
		'offset' => 0,
		'count' => false,
		'selects' => [],
		'wheres' => [],
		'joins' => [],

		'distinct' => true,
		'preload_owners' => false,
		'callback' => $callback,

		'batch' => false,
		'batch_inc_offset' => true,
		'batch_size' => 25,
	];

	$options = array_merge($defaults, $options);

	if ($options['batch'] && !$options['count']) {
		$batch_size = $options['batch_size'];
		$batch_inc_offset = $options['batch_inc_offset'];

		// clean batch keys from $options.
		unset($options['batch'], $options['batch_size'], $options['batch_inc_offset']);

		return new \ElggBatch('_elgg_get_metastring_based_objects', $options, null, $batch_size, $batch_inc_offset);
	}

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

	$singulars = [
		'type', 'subtype', 'type_subtype_pair',
		'guid', 'owner_guid', 'container_guid',
		'metastring_name', 'metastring_value',
		'metastring_owner_guid', 'metastring_id',
		'select', 'where', 'join'
	];

	$options = _elgg_normalize_plural_options_array($options, $singulars);

	if (!$options) {
		return false;
	}

	$db_prefix = elgg_get_config('dbprefix');

	// evaluate where clauses
	if (!is_array($options['wheres'])) {
		$options['wheres'] = [$options['wheres']];
	}

	$wheres = $options['wheres'];

	// entities
	$wheres[] = _elgg_services()->entityTable->getEntityTypeSubtypeWhereSql('e', $options['types'],
		$options['subtypes'], $options['type_subtype_pairs']);

	$wheres[] = _elgg_get_guid_based_where_sql('e.guid', $options['guids']);
	$wheres[] = _elgg_get_guid_based_where_sql('e.owner_guid', $options['owner_guids']);
	$wheres[] = _elgg_get_guid_based_where_sql('e.container_guid', $options['container_guids']);

	$wheres[] = _elgg_get_entity_time_where_sql('e', $options['created_time_upper'],
		$options['created_time_lower'], $options['modified_time_upper'], $options['modified_time_lower']);


	$wheres[] = _elgg_get_entity_time_where_sql('n_table', $options['metastring_created_time_upper'],
		$options['metastring_created_time_lower'], null, null);

	$wheres[] = _elgg_get_guid_based_where_sql('n_table.owner_guid',
		$options['metastring_owner_guids']);

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

	// evaluate join clauses
	if (!is_array($options['joins'])) {
		$options['joins'] = [$options['joins']];
	}

	$joins = [];
	$joins[] = "JOIN {$db_prefix}entities e ON n_table.entity_guid = e.guid";

	// evaluate selects
	if (!is_array($options['selects'])) {
		$options['selects'] = [$options['selects']];
	}

	$selects = $options['selects'];

	// add optional joins
	$joins = array_merge($joins, $options['joins']);

	// metastrings
	$metastring_clauses = _elgg_get_metastring_sql('n_table', $options['metastring_names'],
		$options['metastring_values'], null, $options['metastring_ids'],
		$options['metastring_case_sensitive'], $type);

	if ($metastring_clauses) {
		$wheres = array_merge($wheres, $metastring_clauses['wheres']);
		$joins = array_merge($joins, $metastring_clauses['joins']);
	} else {
		if ($type === 'annotations') {
			$wheres[] = _elgg_get_access_where_sql([
				'table_alias' => 'n_table',
				'guid_column' => 'entity_guid',
			]);
		}
	}

	$distinct = $options['distinct'] ? "DISTINCT " : "";

	if ($options['metastring_calculation'] === ELGG_ENTITIES_NO_VALUE && !$options['count']) {
		$selects = array_unique($selects);
		// evalutate selects
		$select_str = '';
		if ($selects) {
			foreach ($selects as $select) {
				$select_str .= ", $select";
			}
		}

		$query = "SELECT $distinct n_table.*{$select_str} FROM {$db_prefix}$type n_table";
	} elseif ($options['count']) {
		// count is over the entities
		$query = "SELECT count($distinct e.guid) as calculation FROM {$db_prefix}$type n_table";
	} else {
		$query = "SELECT {$options['metastring_calculation']}(n_table.value) as calculation FROM {$db_prefix}$type n_table";
	}

	foreach ($joins as $i => $join) {
		if ($join === false) {
			return false;
		} elseif (empty($join)) {
			unset($joins[$i]);
		}
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
	$query .= _elgg_get_access_where_sql(['table_alias' => 'e']);

	// reverse order by
	if (isset($options['reverse_order_by']) && $options['reverse_order_by']) {
		$options['order_by'] = _elgg_sql_reverse_order_by_clause($options['order_by']);
	}

	if ($options['metastring_calculation'] === ELGG_ENTITIES_NO_VALUE && !$options['count']) {
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

		if ($options['preload_owners'] && is_array($dt) && count($dt) > 1) {
			_elgg_services()->entityPreloader->preload($dt, ['owner_guid']);
		}

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
 * @param string $type           "metadata" or "annotations"
 *
 * @return array
 * @access private
 */
function _elgg_get_metastring_sql($table, $names = null, $values = null,
	$pairs = null, $ids = null, $case_sensitive = false, $type = null) {

	if ($type !== 'metadata' && $type !== 'annotations') {
		throw new \InvalidArgumentException('$type must be "metadata" or "annotations"');
	}

	if ((!$names && $names !== 0)
		&& (!$values && $values !== 0)
		&& !$ids
		&& (!$pairs && $pairs !== 0)) {
		return [];
	}

	$db_prefix = elgg_get_config('dbprefix');

	// binary forces byte-to-byte comparision of strings, making
	// it case- and diacritical-mark- sensitive.
	// only supported on values.
	$binary = ($case_sensitive) ? ' BINARY ' : '';

	$return =  [
		'joins' =>  [],
		'wheres' => []
	];

	$wheres = [];

	// get names wheres and joins
	$names_where = '';
	if ($names !== null) {
		if (!is_array($names)) {
			$names = [$names];
		}

		$sanitised_names = [];
		foreach ($names as $name) {
			// normalise to 0.
			if (!$name) {
				$name = '0';
			}
			$sanitised_names[] = '\'' . sanitise_string($name) . '\'';
		}

		if ($names_str = implode(',', $sanitised_names)) {
			$names_where = "($table.name IN ($names_str))";
		}
	}

	// get values wheres and joins
	$values_where = '';
	if ($values !== null) {
		if (!is_array($values)) {
			$values = [$values];
		}

		$sanitised_values = [];
		foreach ($values as $value) {
			// normalize to 0
			if (!$value) {
				$value = 0;
			}
			$sanitised_values[] = '\'' . sanitise_string($value) . '\'';
		}

		if ($values_str = implode(',', $sanitised_values)) {
			$values_where = "({$binary}$table.value IN ($values_str))";
		}
	}
	
	if ($ids !== null) {
		if (!is_array($ids)) {
			$ids = [$ids];
		}
		$ids_str = implode(',', $ids);
		if ($ids_str) {
			$wheres[] = "n_table.id IN ($ids_str)";
		}
	}

	if ($names_where && $values_where) {
		$wheres[] = "($names_where AND $values_where)";
	} elseif ($names_where) {
		$wheres[] = $names_where;
	} elseif ($values_where) {
		$wheres[] = $values_where;
	}

	if ($type === 'annotations') {
		$wheres[] = _elgg_get_access_where_sql([
			'table_alias' => $table,
			'guid_column' => 'entity_guid',
		]);
	}

	if ($where = implode(' AND ', $wheres)) {
		$return['wheres'][] = "($where)";
	}

	return $return;
}

/**
 * Normalizes metadata / annotation option names to their corresponding metastrings name.
 *
 * @param array $options An options array
 * @return array
 * @access private
 */
function _elgg_normalize_metastrings_options(array $options = []) {

	// support either metastrings_type or metastring_type
	// because I've made this mistake many times and hunting it down is a pain...
	$type = elgg_extract('metastring_type', $options, null);
	$type = elgg_extract('metastrings_type', $options, $type);

	$options['metastring_type'] = $type;

	// support annotation_ and annotations_ because they're way too easy to confuse
	$prefixes = ['metadata_', 'annotation_', 'annotations_'];

	// map the metadata_* options to metastring_* options
	$map = [
		'names'                 => 'metastring_names',
		'values'                => 'metastring_values',
		'case_sensitive'        => 'metastring_case_sensitive',
		'owner_guids'           => 'metastring_owner_guids',
		'created_time_lower'    => 'metastring_created_time_lower',
		'created_time_upper'    => 'metastring_created_time_upper',
		'calculation'           => 'metastring_calculation',
		'ids'                   => 'metastring_ids',
	];

	foreach ($prefixes as $prefix) {
		$singulars = ["{$prefix}name", "{$prefix}value", "{$prefix}owner_guid", "{$prefix}id"];
		$options = _elgg_normalize_plural_options_array($options, $singulars);

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
 * @param string $type    Metastring type: metadata or annotation
 *
 * @return bool
 * @throws InvalidParameterException
 * @access private
 */
function _elgg_set_metastring_based_object_enabled_by_id($id, $enabled, $type) {
	$id = (int) $id;
	$db_prefix = elgg_get_config('dbprefix');

	$object = _elgg_get_metastring_based_object_from_id($id, $type);

	switch ($type) {
		case 'annotation':
		case 'annotations':
			$type = 'annotation';
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
			$return = update_data("UPDATE $table SET enabled = :enabled where id = :id", [
				':enabled' => $enabled,
				':id' => $id,
			]);
		}
	}

	return $return;
}

/**
 * Runs metastrings-based objects found using $options through $callback
 *
 * @warning Unlike _elgg_get_metastring_based_objects() this will not accept an
 * empty options array!
 *
 * @warning This returns null on no ops.
 *
 * @param array  $options    An options array. {@link _elgg_get_metastring_based_objects()}
 * @param string $callback   The callback to pass each result through
 * @param bool   $inc_offset Increment the offset? Pass false for callbacks that delete / disable
 *
 * @return bool|null true on success, false on failure, null if no objects are found.
 * @access private
 */
function _elgg_batch_metastring_based_objects(array $options, $callback, $inc_offset = true) {
	if (!$options || !is_array($options)) {
		return false;
	}

	$batch = new \ElggBatch('_elgg_get_metastring_based_objects', $options, $callback, 50, $inc_offset);
	return $batch->callbackResult;
}

/**
 * Returns a singular metastring-based object by its ID.
 *
 * @param int    $id   The metastring-based object's ID
 * @param string $type The type: annotation or metadata
 * @return \ElggExtender
 * @access private
 */
function _elgg_get_metastring_based_object_from_id($id, $type) {
	$id = (int) $id;
	if (!$id) {
		return false;
	}

	$options = [
		'metastring_type' => $type,
		'metastring_id' => $id,
	];

	$obj = _elgg_get_metastring_based_objects($options);

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
 * @access private
 */
function _elgg_delete_metastring_based_object_by_id($id, $type) {
	$id = (int) $id;
	$db_prefix = elgg_get_config('dbprefix');

	switch ($type) {
		case 'annotations':
		case 'annotation':
			$table = $db_prefix . 'annotations';
			$type = 'annotation';
			break;

		case 'metadata':
			$table = $db_prefix . 'metadata';
			$type = 'metadata';
			break;

		default:
			return false;
	}

	$obj = _elgg_get_metastring_based_object_from_id($id, $type);

	if ($obj) {
		if ($obj->canEdit()) {
			if (elgg_trigger_event('delete', $type, $obj)) {
				return (bool) delete_data("DELETE FROM $table WHERE id = :id", [
					':id' => $id,
				]);
			}
		}
	}

	return false;
}

/**
 * Returns options to pass to elgg_get_entities() for metastrings operations.
 *
 * @param string $type    Metastring type: annotation or metadata
 * @param array  $options Options
 *
 * @return array
 * @access private
 */
function _elgg_entities_get_metastrings_options($type, $options) {
	$valid_types = ['metadata', 'annotation'];
	if (!in_array($type, $valid_types)) {
		return false;
	}

	// the options for annotations are singular (annotation_name) but the table
	// is plural (elgg_annotations) so rewrite for the table name.
	$n_table = ($type == 'annotation') ? 'annotations' : $type;

	$singulars = ["{$type}_name", "{$type}_value",
		"{$type}_name_value_pair", "{$type}_owner_guid"];
	$options = _elgg_normalize_plural_options_array($options, $singulars);

	$clauses = _elgg_get_entity_metadata_where_sql('e', $n_table, $options["{$type}_names"],
		$options["{$type}_values"], $options["{$type}_name_value_pairs"],
		$options["{$type}_name_value_pairs_operator"], $options["{$type}_case_sensitive"],
		$options["order_by_{$type}"], $options["{$type}_owner_guids"]);

	if ($clauses) {
		// merge wheres to pass to elgg_get_entities()
		if (isset($options['wheres']) && !is_array($options['wheres'])) {
			$options['wheres'] = [$options['wheres']];
		} elseif (!isset($options['wheres'])) {
			$options['wheres'] = [];
		}

		$options['wheres'] = array_merge($options['wheres'], $clauses['wheres']);

		// merge joins to pass to elgg_get_entities()
		if (isset($options['joins']) && !is_array($options['joins'])) {
			$options['joins'] = [$options['joins']];
		} elseif (!isset($options['joins'])) {
			$options['joins'] = [];
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

/**
 * Metastring unit tests
 *
 * @param string $hook  unit_test
 * @param string $type  system
 * @param array  $value Array of other tests
 *
 * @return array
 * @access private
 */
function _elgg_metastrings_test($hook, $type, $value) {
	global $CONFIG;
	$value[] = $CONFIG->path . 'engine/tests/ElggCoreMetastringsTest.php';
	return $value;
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$hooks->registerHandler('unit_test', 'system', '_elgg_metastrings_test');
};
