<?php
/**
 * Elgg metadata
 * Functions to manage entity metadata.
 *
 * @package Elgg.Core
 * @subpackage DataModel.Metadata
 */

/**
 * Convert a database row to a new ElggMetadata
 *
 * @param stdClass $row An object from the database
 *
 * @return stdClass|ElggMetadata
 * @access private
 */
function row_to_elggmetadata($row) {
	if (!($row instanceof stdClass)) {
		return $row;
	}

	return new ElggMetadata($row);
}

/**
 * Get a specific metadata object by its id.
 * If you want multiple metadata objects, use
 * {@link elgg_get_metadata()}.
 *
 * @param int $id The id of the metadata object being retrieved.
 *
 * @return ElggMetadata|false  FALSE if not found
 */
function elgg_get_metadata_from_id($id) {
	return elgg_get_metastring_based_object_from_id($id, 'metadata');
}

/**
 * Deletes metadata using its ID.
 *
 * @param int $id The metadata ID to delete.
 * @return bool
 */
function elgg_delete_metadata_by_id($id) {
	$metadata = elgg_get_metadata_from_id($id);
	if (!$metadata) {
		return false;
	}
	return $metadata->delete();
}

/**
 * Create a new metadata object, or update an existing one.
 *
 * Metadata can be an array by setting allow_multiple to TRUE, but it is an
 * indexed array with no control over the indexing.
 *
 * @param int    $entity_guid    The entity to attach the metadata to
 * @param string $name           Name of the metadata
 * @param string $value          Value of the metadata
 * @param string $value_type     'text', 'integer', or '' for automatic detection
 * @param int    $owner_guid     GUID of entity that owns the metadata
 * @param int    $access_id      Default is ACCESS_PRIVATE
 * @param bool   $allow_multiple Allow multiple values for one key. Default is FALSE
 *
 * @return int|false id of metadata or FALSE if failure
 */
function create_metadata($entity_guid, $name, $value, $value_type = '', $owner_guid = 0,
	$access_id = ACCESS_PRIVATE, $allow_multiple = false) {

	global $CONFIG;

	$entity_guid = (int)$entity_guid;
	// name and value are encoded in add_metastring()
	//$name = sanitise_string(trim($name));
	//$value = sanitise_string(trim($value));
	$value_type = detect_extender_valuetype($value, sanitise_string(trim($value_type)));
	$time = time();
	$owner_guid = (int)$owner_guid;
	$allow_multiple = (boolean)$allow_multiple;

	if (!isset($value)) {
		return FALSE;
	}

	if ($owner_guid == 0) {
		$owner_guid = elgg_get_logged_in_user_guid();
	}

	$access_id = (int)$access_id;

	$query = "SELECT * from {$CONFIG->dbprefix}metadata"
		. " WHERE entity_guid = $entity_guid and name_id=" . add_metastring($name) . " limit 1";

	$existing = get_data_row($query);
	if ($existing && !$allow_multiple) {
		$id = (int)$existing->id;
		$result = update_metadata($id, $name, $value, $value_type, $owner_guid, $access_id);

		if (!$result) {
			return false;
		}
	} else {
		// Support boolean types
		if (is_bool($value)) {
			$value = (int) $value;
		}

		// Add the metastrings
		$value_id = add_metastring($value);
		if (!$value_id) {
			return false;
		}

		$name_id = add_metastring($name);
		if (!$name_id) {
			return false;
		}

		// If ok then add it
		$query = "INSERT into {$CONFIG->dbprefix}metadata"
			. " (entity_guid, name_id, value_id, value_type, owner_guid, time_created, access_id)"
			. " VALUES ($entity_guid, '$name_id','$value_id','$value_type', $owner_guid, $time, $access_id)";

		$id = insert_data($query);

		if ($id !== false) {
			$obj = elgg_get_metadata_from_id($id);
			if (elgg_trigger_event('create', 'metadata', $obj)) {

				elgg_get_metadata_cache()->save($entity_guid, $name, $value, $allow_multiple);

				return $id;
			} else {
				elgg_delete_metadata_by_id($id);
			}
		}
	}

	return $id;
}

/**
 * Update a specific piece of metadata.
 *
 * @param int    $id         ID of the metadata to update
 * @param string $name       Metadata name
 * @param string $value      Metadata value
 * @param string $value_type Value type
 * @param int    $owner_guid Owner guid
 * @param int    $access_id  Access ID
 *
 * @return bool
 */
function update_metadata($id, $name, $value, $value_type, $owner_guid, $access_id) {
	global $CONFIG;

	$id = (int)$id;

	if (!$md = elgg_get_metadata_from_id($id)) {
		return false;
	}
	if (!$md->canEdit()) {
		return false;
	}

	// If memcached then we invalidate the cache for this entry
	static $metabyname_memcache;
	if ((!$metabyname_memcache) && (is_memcache_available())) {
		$metabyname_memcache = new ElggMemcache('metabyname_memcache');
	}

	if ($metabyname_memcache) {
		// @todo fix memcache (name_id is not a property of ElggMetadata)
		$metabyname_memcache->delete("{$md->entity_guid}:{$md->name_id}");
	}

	$value_type = detect_extender_valuetype($value, sanitise_string(trim($value_type)));

	$owner_guid = (int)$owner_guid;
	if ($owner_guid == 0) {
		$owner_guid = elgg_get_logged_in_user_guid();
	}

	$access_id = (int)$access_id;

	// Support boolean types (as integers)
	if (is_bool($value)) {
		$value = (int) $value;
	}

	// Add the metastring
	$value_id = add_metastring($value);
	if (!$value_id) {
		return false;
	}

	$name_id = add_metastring($name);
	if (!$name_id) {
		return false;
	}

	// If ok then add it
	$query = "UPDATE {$CONFIG->dbprefix}metadata"
		. " set name_id='$name_id', value_id='$value_id', value_type='$value_type', access_id=$access_id,"
		. " owner_guid=$owner_guid where id=$id";

	$result = update_data($query);
	if ($result !== false) {

		elgg_get_metadata_cache()->save($md->entity_guid, $name, $value);

		// @todo this event tells you the metadata has been updated, but does not
		// let you do anything about it. What is needed is a plugin hook before
		// the update that passes old and new values.
		$obj = elgg_get_metadata_from_id($id);
		elgg_trigger_event('update', 'metadata', $obj);
	}

	return $result;
}

/**
 * This function creates metadata from an associative array of "key => value" pairs.
 *
 * To achieve an array for a single key, pass in the same key multiple times with
 * allow_multiple set to TRUE. This creates an indexed array. It does not support
 * associative arrays and there is no guarantee on the ordering in the array.
 *
 * @param int    $entity_guid     The entity to attach the metadata to
 * @param array  $name_and_values Associative array - a value can be a string, number, bool
 * @param string $value_type      'text', 'integer', or '' for automatic detection
 * @param int    $owner_guid      GUID of entity that owns the metadata
 * @param int    $access_id       Default is ACCESS_PRIVATE
 * @param bool   $allow_multiple  Allow multiple values for one key. Default is FALSE
 *
 * @return bool
 */
function create_metadata_from_array($entity_guid, array $name_and_values, $value_type, $owner_guid,
$access_id = ACCESS_PRIVATE, $allow_multiple = false) {

	foreach ($name_and_values as $k => $v) {
		$result = create_metadata($entity_guid, $k, $v, $value_type, $owner_guid,
			$access_id, $allow_multiple);
		if (!$result) {
			return false;
		}
	}
	return true;
}

/**
 * Returns metadata.  Accepts all elgg_get_entities() options for entity
 * restraints.
 *
 * @see elgg_get_entities
 *
 * @warning 1.7's find_metadata() didn't support limits and returned all metadata.
 *          This function defaults to a limit of 25. There is probably not a reason
 *          for you to return all metadata unless you're exporting an entity,
 *          have other restraints in place, or are doing something horribly
 *          wrong in your code.
 *
 * @param array $options Array in format:
 *
 * metadata_names               => NULL|ARR metadata names
 * metadata_values              => NULL|ARR metadata values
 * metadata_ids                 => NULL|ARR metadata ids
 * metadata_case_sensitive      => BOOL Overall Case sensitive
 * metadata_owner_guids         => NULL|ARR guids for metadata owners
 * metadata_created_time_lower  => INT Lower limit for created time.
 * metadata_created_time_upper  => INT Upper limit for created time.
 * metadata_calculation         => STR Perform the MySQL function on the metadata values returned.
 *                                   The "metadata_calculation" option causes this function to
 *                                   return the result of performing a mathematical calculation on
 *                                   all metadata that match the query instead of returning
 *                                   ElggMetadata objects.
 *
 * @return ElggMetadata[]|mixed
 * @since 1.8.0
 */
function elgg_get_metadata(array $options = array()) {

	// @todo remove support for count shortcut - see #4393
	// support shortcut of 'count' => true for 'metadata_calculation' => 'count'
	if (isset($options['count']) && $options['count']) {
		$options['metadata_calculation'] = 'count';
		unset($options['count']);
	}

	$options['metastring_type'] = 'metadata';
	return elgg_get_metastring_based_objects($options);
}

/**
 * Deletes metadata based on $options.
 *
 * @warning Unlike elgg_get_metadata() this will not accept an empty options array!
 *          This requires at least one constraint: metadata_owner_guid(s),
 *          metadata_name(s), metadata_value(s), or guid(s) must be set.
 *
 * @param array $options An options array. {@see elgg_get_metadata()}
 * @return bool|null true on success, false on failure, null if no metadata to delete.
 * @since 1.8.0
 */
function elgg_delete_metadata(array $options) {
	if (!elgg_is_valid_options_for_batch_operation($options, 'metadata')) {
		return false;
	}
	$options['metastring_type'] = 'metadata';
	$result = elgg_batch_metastring_based_objects($options, 'elgg_batch_delete_callback', false);

	// This moved last in case an object's constructor sets metadata. Currently the batch
	// delete process has to create the entity to delete its metadata. See #5214
	elgg_get_metadata_cache()->invalidateByOptions('delete', $options);

	return $result;
}

/**
 * Disables metadata based on $options.
 *
 * @warning Unlike elgg_get_metadata() this will not accept an empty options array!
 *
 * @param array $options An options array. {@See elgg_get_metadata()}
 * @return bool|null true on success, false on failure, null if no metadata disabled.
 * @since 1.8.0
 */
function elgg_disable_metadata(array $options) {
	if (!elgg_is_valid_options_for_batch_operation($options, 'metadata')) {
		return false;
	}

	elgg_get_metadata_cache()->invalidateByOptions('disable', $options);
	
	// if we can see hidden (disabled) we need to use the offset
	// otherwise we risk an infinite loop if there are more than 50
	$inc_offset = access_get_show_hidden_status();

	$options['metastring_type'] = 'metadata';
	return elgg_batch_metastring_based_objects($options, 'elgg_batch_disable_callback', $inc_offset);
}

/**
 * Enables metadata based on $options.
 *
 * @warning Unlike elgg_get_metadata() this will not accept an empty options array!
 *
 * @warning In order to enable metadata, you must first use
 * {@link access_show_hidden_entities()}.
 *
 * @param array $options An options array. {@See elgg_get_metadata()}
 * @return bool|null true on success, false on failure, null if no metadata enabled.
 * @since 1.8.0
 */
function elgg_enable_metadata(array $options) {
	if (!$options || !is_array($options)) {
		return false;
	}

	elgg_get_metadata_cache()->invalidateByOptions('enable', $options);

	$options['metastring_type'] = 'metadata';
	return elgg_batch_metastring_based_objects($options, 'elgg_batch_enable_callback');
}

/**
 * ElggEntities interfaces
 */

/**
 * Returns entities based upon metadata.  Also accepts all
 * options available to elgg_get_entities().  Supports
 * the singular option shortcut.
 *
 * @note Using metadata_names and metadata_values results in a
 * "names IN (...) AND values IN (...)" clause.  This is subtly
 * differently than default multiple metadata_name_value_pairs, which use
 * "(name = value) AND (name = value)" clauses.
 *
 * When in doubt, use name_value_pairs.
 *
 * To ask for entities that do not have a metadata value, use a custom
 * where clause like this:
 *
 * 	$options['wheres'][] = "NOT EXISTS (
 *			SELECT 1 FROM {$dbprefix}metadata md
 *			WHERE md.entity_guid = e.guid
 *				AND md.name_id = $name_metastring_id
 *				AND md.value_id = $value_metastring_id)";
 *
 * Note the metadata name and value has been denormalized in the above example.
 *
 * @see elgg_get_entities
 *
 * @param array $options Array in format:
 *
 * 	metadata_names => NULL|ARR metadata names
 *
 * 	metadata_values => NULL|ARR metadata values
 *
 * 	metadata_name_value_pairs => NULL|ARR (
 *                                         name => 'name',
 *                                         value => 'value',
 *                                         'operand' => '=',
 *                                         'case_sensitive' => TRUE
 *                                        )
 *                               Currently if multiple values are sent via
 *                               an array (value => array('value1', 'value2')
 *                               the pair's operand will be forced to "IN".
 *                               If passing "IN" as the operand and a string as the value, 
 *                               the value must be a properly quoted and escaped string.
 *
 * 	metadata_name_value_pairs_operator => NULL|STR The operator to use for combining
 *                                        (name = value) OPERATOR (name = value); default AND
 *
 * 	metadata_case_sensitive => BOOL Overall Case sensitive
 *
 *  order_by_metadata => NULL|ARR array(
 *                                      'name' => 'metadata_text1',
 *                                      'direction' => ASC|DESC,
 *                                      'as' => text|integer
 *                                     )
 *                                Also supports array('name' => 'metadata_text1')
 *
 *  metadata_owner_guids => NULL|ARR guids for metadata owners
 *
 * @return ElggEntity[]|mixed If count, int. If not count, array. false on errors.
 * @since 1.7.0
 */
function elgg_get_entities_from_metadata(array $options = array()) {
	$defaults = array(
		'metadata_names'                     => ELGG_ENTITIES_ANY_VALUE,
		'metadata_values'                    => ELGG_ENTITIES_ANY_VALUE,
		'metadata_name_value_pairs'          => ELGG_ENTITIES_ANY_VALUE,

		'metadata_name_value_pairs_operator' => 'AND',
		'metadata_case_sensitive'            => TRUE,
		'order_by_metadata'                  => array(),

		'metadata_owner_guids'               => ELGG_ENTITIES_ANY_VALUE,
	);

	$options = array_merge($defaults, $options);

	$singulars = array('metadata_name', 'metadata_value',
		'metadata_name_value_pair', 'metadata_owner_guid');

	$options = elgg_normalise_plural_options_array($options, $singulars);

	if (!$options = elgg_entities_get_metastrings_options('metadata', $options)) {
		return FALSE;
	}

	return elgg_get_entities($options);
}

/**
 * Returns metadata name and value SQL where for entities.
 * NB: $names and $values are not paired. Use $pairs for this.
 * Pairs default to '=' operand.
 *
 * This function is reused for annotations because the tables are
 * exactly the same.
 *
 * @param string     $e_table           Entities table name
 * @param string     $n_table           Normalized metastrings table name (Where entities,
 *                                    values, and names are joined. annotations / metadata)
 * @param array|null $names             Array of names
 * @param array|null $values            Array of values
 * @param array|null $pairs             Array of names / values / operands
 * @param string     $pair_operator     ("AND" or "OR") Operator to use to join the where clauses for pairs
 * @param bool       $case_sensitive    Case sensitive metadata names?
 * @param array|null $order_by_metadata Array of names / direction
 * @param array|null $owner_guids       Array of owner GUIDs
 *
 * @return false|array False on fail, array('joins', 'wheres')
 * @since 1.7.0
 * @access private
 */
function elgg_get_entity_metadata_where_sql($e_table, $n_table, $names = NULL, $values = NULL,
$pairs = NULL, $pair_operator = 'AND', $case_sensitive = TRUE, $order_by_metadata = NULL,
$owner_guids = NULL) {

	global $CONFIG;

	// short circuit if nothing requested
	// 0 is a valid (if not ill-conceived) metadata name.
	// 0 is also a valid metadata value for FALSE, NULL, or 0
	// 0 is also a valid(ish) owner_guid
	if ((!$names && $names !== 0)
		&& (!$values && $values !== 0)
		&& (!$pairs && $pairs !== 0)
		&& (!$owner_guids && $owner_guids !== 0)
		&& !$order_by_metadata) {
		return '';
	}

	// join counter for incremental joins.
	$i = 1;

	// binary forces byte-to-byte comparision of strings, making
	// it case- and diacritical-mark- sensitive.
	// only supported on values.
	$binary = ($case_sensitive) ? ' BINARY ' : '';

	$access = get_access_sql_suffix('n_table');

	$return = array (
		'joins' => array (),
		'wheres' => array(),
		'orders' => array()
	);

	// will always want to join these tables if pulling metastrings.
	$return['joins'][] = "JOIN {$CONFIG->dbprefix}{$n_table} n_table on
		{$e_table}.guid = n_table.entity_guid";

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
			$return['joins'][] = "JOIN {$CONFIG->dbprefix}metastrings msn on n_table.name_id = msn.id";
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
			$return['joins'][] = "JOIN {$CONFIG->dbprefix}metastrings msv on n_table.value_id = msv.id";
			$values_where = "({$binary}msv.string IN ($values_str))";
		}
	}

	if ($names_where && $values_where) {
		$wheres[] = "($names_where AND $values_where AND $access)";
	} elseif ($names_where) {
		$wheres[] = "($names_where AND $access)";
	} elseif ($values_where) {
		$wheres[] = "($values_where AND $access)";
	}

	// add pairs
	// pairs must be in arrays.
	if (is_array($pairs)) {
		// check if this is an array of pairs or just a single pair.
		if (isset($pairs['name']) || isset($pairs['value'])) {
			$pairs = array($pairs);
		}

		$pair_wheres = array();

		// @todo when the pairs are > 3 should probably split the query up to
		// denormalize the strings table.

		foreach ($pairs as $index => $pair) {
			// @todo move this elsewhere?
			// support shortcut 'n' => 'v' method.
			if (!is_array($pair)) {
				$pair = array(
					'name' => $index,
					'value' => $pair
				);
			}

			// must have at least a name and value
			if (!isset($pair['name']) || !isset($pair['value'])) {
				// @todo should probably return false.
				continue;
			}

			// case sensitivity can be specified per pair.
			// default to higher level setting.
			if (isset($pair['case_sensitive'])) {
				$pair_binary = ($pair['case_sensitive']) ? ' BINARY ' : '';
			} else {
				$pair_binary = $binary;
			}

			if (isset($pair['operand'])) {
				$operand = sanitise_string($pair['operand']);
			} else {
				$operand = ' = ';
			}

			// for comparing
			$trimmed_operand = trim(strtolower($operand));

			$access = get_access_sql_suffix("n_table{$i}");
			// if the value is an int, don't quote it because str '15' < str '5'
			// if the operand is IN don't quote it because quoting should be done already.
			if (is_numeric($pair['value'])) {
				$value = sanitise_string($pair['value']);
			} else if (is_bool($pair['value'])) {
				$value = (int) $pair['value'];
			} else if (is_array($pair['value'])) {
				$values_array = array();

				foreach ($pair['value'] as $pair_value) {
					if (is_numeric($pair_value)) {
						$values_array[] = sanitise_string($pair_value);
					} else {
						$values_array[] = "'" . sanitise_string($pair_value) . "'";
					}
				}

				if ($values_array) {
					$value = '(' . implode(', ', $values_array) . ')';
				}

				// @todo allow support for non IN operands with array of values.
				// will have to do more silly joins.
				$operand = 'IN';
			} else if ($trimmed_operand == 'in') {
				$value = "({$pair['value']})";
			} else {
				$value = "'" . sanitise_string($pair['value']) . "'";
			}

			$name = sanitise_string($pair['name']);

			// @todo The multiple joins are only needed when the operator is AND
			$return['joins'][] = "JOIN {$CONFIG->dbprefix}{$n_table} n_table{$i}
				on {$e_table}.guid = n_table{$i}.entity_guid";
			$return['joins'][] = "JOIN {$CONFIG->dbprefix}metastrings msn{$i}
				on n_table{$i}.name_id = msn{$i}.id";
			$return['joins'][] = "JOIN {$CONFIG->dbprefix}metastrings msv{$i}
				on n_table{$i}.value_id = msv{$i}.id";

			$pair_wheres[] = "(msn{$i}.string = '$name' AND {$pair_binary}msv{$i}.string
				$operand $value AND $access)";

			$i++;
		}

		if ($where = implode(" $pair_operator ", $pair_wheres)) {
			$wheres[] = "($where)";
		}
	}

	// add owner_guids
	if ($owner_guids) {
		if (is_array($owner_guids)) {
			$sanitised = array_map('sanitise_int', $owner_guids);
			$owner_str = implode(',', $sanitised);
		} else {
			$owner_str = sanitise_int($owner_guids);
		}

		$wheres[] = "(n_table.owner_guid IN ($owner_str))";
	}

	if ($where = implode(' AND ', $wheres)) {
		$return['wheres'][] = "($where)";
	}

	if (is_array($order_by_metadata)) {
		if ((count($order_by_metadata) > 0) && !isset($order_by_metadata[0])) {
			// singleton, so fix
			$order_by_metadata = array($order_by_metadata);
		}
		foreach ($order_by_metadata as $order_by) {
			if (is_array($order_by) && isset($order_by['name'])) {
				$name = sanitise_string($order_by['name']);
				if (isset($order_by['direction'])) {
					$direction = sanitise_string($order_by['direction']);
				} else {
					$direction = 'ASC';
				}
				$return['joins'][] = "JOIN {$CONFIG->dbprefix}{$n_table} n_table{$i}
					on {$e_table}.guid = n_table{$i}.entity_guid";
				$return['joins'][] = "JOIN {$CONFIG->dbprefix}metastrings msn{$i}
					on n_table{$i}.name_id = msn{$i}.id";
				$return['joins'][] = "JOIN {$CONFIG->dbprefix}metastrings msv{$i}
					on n_table{$i}.value_id = msv{$i}.id";

				$access = get_access_sql_suffix("n_table{$i}");

				$return['wheres'][] = "(msn{$i}.string = '$name' AND $access)";
				if (isset($order_by['as']) && $order_by['as'] == 'integer') {
					$return['orders'][] = "CAST(msv{$i}.string AS SIGNED) $direction";
				} else {
					$return['orders'][] = "msv{$i}.string $direction";
				}
				$i++;
			}
		}
	}

	return $return;
}

/**
 * Returns a list of entities filtered by provided metadata.
 *
 * @see elgg_get_entities_from_metadata
 *
 * @param array $options Options array
 *
 * @return array
 * @since 1.7.0
 */
function elgg_list_entities_from_metadata($options) {
	return elgg_list_entities($options, 'elgg_get_entities_from_metadata');
}

/**
 * Other functions
 */

/**
 * Handler called by trigger_plugin_hook on the "export" event.
 *
 * @param string $hook        export
 * @param string $entity_type all
 * @param mixed  $returnvalue Value returned from previous hook
 * @param mixed  $params      Params
 *
 * @return array
 * @access private
 *
 * @throws InvalidParameterException
 */
function export_metadata_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	// Sanity check values
	if ((!is_array($params)) && (!isset($params['guid']))) {
		throw new InvalidParameterException(elgg_echo('InvalidParameterException:GUIDNotForExport'));
	}

	if (!is_array($returnvalue)) {
		throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonArrayReturnValue'));
	}

	$result = elgg_get_metadata(array(
		'guid' => (int)$params['guid'],
		'limit' => 0,
	));

	if ($result) {
		/* @var ElggMetadata[] $result */
		foreach ($result as $r) {
			$returnvalue[] = $r->export();
		}
	}

	return $returnvalue;
}

/**
 * Takes in a comma-separated string and returns an array of tags
 * which have been trimmed
 *
 * @param string $string Comma-separated tag string
 *
 * @return array|false An array of strings, or false on failure
 */
function string_to_tag_array($string) {
	if (is_string($string)) {
		$ar = explode(",", $string);
		$ar = array_map('trim', $ar);
		$ar = array_filter($ar, 'is_not_null');
		$ar = array_map('strip_tags', $ar);
		return $ar;
	}
	return false;
}

/**
 * Takes a metadata array (which has all kinds of properties)
 * and turns it into a simple array of strings
 *
 * @param array $array Metadata array
 *
 * @return array Array of strings
 */
function metadata_array_to_values($array) {
	$valuearray = array();

	if (is_array($array)) {
		foreach ($array as $element) {
			$valuearray[] = $element->value;
		}
	}

	return $valuearray;
}

/**
 * Get the URL for this metadata
 *
 * By default this links to the export handler in the current view.
 *
 * @param int $id Metadata ID
 *
 * @return mixed
 */
function get_metadata_url($id) {
	$id = (int)$id;

	if ($extender = elgg_get_metadata_from_id($id)) {
		return get_extender_url($extender);
	}
	return false;
}

/**
 * Mark entities with a particular type and subtype as having access permissions
 * that can be changed independently from their parent entity
 *
 * @param string $type    The type - object, user, etc
 * @param string $subtype The subtype; all subtypes by default
 *
 * @return void
 */
function register_metadata_as_independent($type, $subtype = '*') {
	global $CONFIG;
	if (!isset($CONFIG->independents)) {
		$CONFIG->independents = array();
	}
	$CONFIG->independents[$type][$subtype] = true;
}

/**
 * Determines whether entities of a given type and subtype should not change
 * their metadata in line with their parent entity
 *
 * @param string $type    The type - object, user, etc
 * @param string $subtype The entity subtype
 *
 * @return bool
 */
function is_metadata_independent($type, $subtype) {
	global $CONFIG;
	if (empty($CONFIG->independents)) {
		return false;
	}
	if (!empty($CONFIG->independents[$type][$subtype])
		|| !empty($CONFIG->independents[$type]['*'])) {
			return true;
		}
	return false;
}

/**
 * When an entity is updated, resets the access ID on all of its child metadata
 *
 * @param string     $event       The name of the event
 * @param string     $object_type The type of object
 * @param ElggEntity $object      The entity itself
 *
 * @return true
 */
function metadata_update($event, $object_type, $object) {
	if ($object instanceof ElggEntity) {
		if (!is_metadata_independent($object->getType(), $object->getSubtype())) {
			$db_prefix = elgg_get_config('dbprefix');
			$access_id = (int) $object->access_id;
			$guid = (int) $object->getGUID();
			$query = "update {$db_prefix}metadata set access_id = {$access_id} where entity_guid = {$guid}";
			update_data($query);
		}
	}
	return true;
}

/**
 * Register a metadata url handler.
 *
 * @param string $extender_name The name, default 'all'.
 * @param string $function      The function name.
 *
 * @return bool
 */
function elgg_register_metadata_url_handler($extender_name, $function) {
	return elgg_register_extender_url_handler('metadata', $extender_name, $function);
}

/**
 * Get the global metadata cache instance
 *
 * @return ElggVolatileMetadataCache
 *
 * @access private
 */
function elgg_get_metadata_cache() {
	global $CONFIG;
	if (empty($CONFIG->local_metadata_cache)) {
		$CONFIG->local_metadata_cache = new ElggVolatileMetadataCache();
	}
	return $CONFIG->local_metadata_cache;
}

/**
 * Invalidate the metadata cache based on options passed to various *_metadata functions
 *
 * @param string $action  Action performed on metadata. "delete", "disable", or "enable"
 * @param array  $options Options passed to elgg_(delete|disable|enable)_metadata
 * @return void
 */
function elgg_invalidate_metadata_cache($action, array $options) {
	// remove as little as possible, optimizing for common cases
	$cache = elgg_get_metadata_cache();
	if (empty($options['guid'])) {
		// safest to clear everything unless we want to make this even more complex :(
		$cache->flush();
	} else {
		if (empty($options['metadata_name'])) {
			// safest to clear the whole entity
			$cache->clear($options['guid']);
		} else {
			switch ($action) {
				case 'delete':
					$cache->markEmpty($options['guid'], $options['metadata_name']);
					break;
				default:
					$cache->markUnknown($options['guid'], $options['metadata_name']);
			}
		}
	}
}

/** Register the hook */
elgg_register_plugin_hook_handler("export", "all", "export_metadata_plugin_hook", 2);

/** Call a function whenever an entity is updated **/
elgg_register_event_handler('update', 'all', 'metadata_update');

// unit testing
elgg_register_plugin_hook_handler('unit_test', 'system', 'metadata_test');

/**
 * Metadata unit test
 *
 * @param string $hook   unit_test
 * @param string $type   system
 * @param mixed  $value  Array of other tests
 * @param mixed  $params Params
 *
 * @return array
 * @access private
 */
function metadata_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = $CONFIG->path . 'engine/tests/api/metadata.php';
	$value[] = $CONFIG->path . 'engine/tests/api/metadata_cache.php';
	return $value;
}
