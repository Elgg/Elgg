<?php
/**
 * Elgg annotations
 * Functions to manage object annotations.
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Convert a database row to a new ElggAnnotation
 *
 * @param stdClass $row Db row result object
 *
 * @return ElggAnnotation
 */
function row_to_elggannotation($row) {
	if (!($row instanceof stdClass)) {
		return $row;
	}

	return new ElggAnnotation($row);
}

/**
 * Get a specific annotation.
 *
 * @param int $annotation_id Annotation ID
 *
 * @return ElggAnnotation
 */
function get_annotation($annotation_id) {
	global $CONFIG;

	$annotation_id = (int) $annotation_id;
	$access = get_access_sql_suffix("a");

	$query = "SELECT a.*, n.string as name, v.string as value"
		. " from {$CONFIG->dbprefix}annotations a"
		. " JOIN {$CONFIG->dbprefix}metastrings n on a.name_id = n.id"
		. " JOIN {$CONFIG->dbprefix}metastrings v on a.value_id = v.id"
		. " where a.id=$annotation_id and $access";

	return row_to_elggannotation(get_data_row($query));
}

/**
 * Create a new annotation.
 *
 * @param int    $entity_guid Entity Guid
 * @param string $name        Name of annotation
 * @param string $value       Value of annotation
 * @param string $value_type  Type of value
 * @param int    $owner_guid  Owner of annotation
 * @param int    $access_id   Access level of annotation
 *
 * @return int|bool id on success or false on failure
 */
function create_annotation($entity_guid, $name, $value, $value_type,
$owner_guid, $access_id = ACCESS_PRIVATE) {
	global $CONFIG;

	$result = false;

	$entity_guid = (int)$entity_guid;
	//$name = sanitise_string(trim($name));
	//$value = sanitise_string(trim($value));
	$value_type = detect_extender_valuetype($value, sanitise_string(trim($value_type)));

	$owner_guid = (int)$owner_guid;
	if ($owner_guid == 0) {
		$owner_guid = elgg_get_logged_in_user_guid();
	}

	$access_id = (int)$access_id;
	$time = time();

	// Add the metastring
	$value = add_metastring($value);
	if (!$value) {
		return false;
	}

	$name = add_metastring($name);
	if (!$name) {
		return false;
	}

	$entity = get_entity($entity_guid);

	if (elgg_trigger_event('annotate', $entity->type, $entity)) {
		system_log($entity, 'annotate');

		// If ok then add it
		$result = insert_data("INSERT into {$CONFIG->dbprefix}annotations
			(entity_guid, name_id, value_id, value_type, owner_guid, time_created, access_id) VALUES
			($entity_guid,'$name',$value,'$value_type', $owner_guid, $time, $access_id)");

		if ($result !== false) {
			$obj = get_annotation($result);
			if (elgg_trigger_event('create', 'annotation', $obj)) {
				return $result;
			} else {
				// plugin returned false to reject annotation
				delete_annotation($result);
				return FALSE;
			}
		}
	}

	return $result;
}

/**
 * Update an annotation.
 *
 * @param int    $annotation_id Annotation ID
 * @param string $name          Name of annotation
 * @param string $value         Value of annotation
 * @param string $value_type    Type of value
 * @param int    $owner_guid    Owner of annotation
 * @param int    $access_id     Access level of annotation
 *
 * @return bool
 */
function update_annotation($annotation_id, $name, $value, $value_type, $owner_guid, $access_id) {
	global $CONFIG;

	$annotation_id = (int)$annotation_id;
	$name = (trim($name));
	$value = (trim($value));
	$value_type = detect_extender_valuetype($value, sanitise_string(trim($value_type)));

	$owner_guid = (int)$owner_guid;
	if ($owner_guid == 0) {
		$owner_guid = elgg_get_logged_in_user_guid();
	}

	$access_id = (int)$access_id;

	$access = get_access_sql_suffix();

	// Add the metastring
	$value = add_metastring($value);
	if (!$value) {
		return false;
	}

	$name = add_metastring($name);
	if (!$name) {
		return false;
	}

	// If ok then add it
	$result = update_data("UPDATE {$CONFIG->dbprefix}annotations
		set value_id='$value', value_type='$value_type', access_id=$access_id, owner_guid=$owner_guid
		where id=$annotation_id and name_id='$name' and $access");

	if ($result !== false) {
		$obj = get_annotation($annotation_id);
		if (elgg_trigger_event('update', 'annotation', $obj)) {
			return true;
		} else {
			// @todo add plugin hook that sends old and new annotation information before db access
			delete_annotation($annotation_id);
		}
	}

	return $result;
}

/**
 * Returns annotations.  Accepts all elgg_get_entities() options for entity
 * restraints.
 *
 * @see elgg_get_entities
 *
 * @param array $options Array in format:
 *
 * 	annotation_names => NULL|ARR Annotation names
 *
 * 	annotation_values => NULL|ARR Annotation values
 *
 * 	annotation_case_sensitive => BOOL Overall Case sensitive
 *
 *  annotation_owner_guids => NULL|ARR guids for annotation owners
 *
 *  annotation_created_time_lower => INT Lower limit for created time.
 *
 *  annotation_created_time_upper => INT Upper limit for created time.
 *
 *  annotation_calculation => STR Perform the MySQL function on the annotation values returned.
 *
 * @return mixed
 * @since 1.8.0
 */
function elgg_get_annotations(array $options = array()) {
	// map the annotation_* options to metastring_* options
	$map = array(
		'annotation_names' => 'metastring_names',
		'annotation_values' => 'metastring_values',
		'annotation_case_sensitive' => 'metastring_case_sensitive',
		'annotation_owner_guids' => 'metastring_owner_guids',
		'annotation_created_time_lower' => 'metastring_created_time_lower',
		'annotation_created_time_upper' => 'metastring_created_time_upper',
		'annotation_calculation' => 'metastring_calculation'
	);

	$singulars = array('annotation_name', 'annotation_value');
	$options = elgg_normalise_plural_options_array($options, $singulars);

	foreach ($map as $ann => $ms) {
		if (isset($options[$ann])) {
			$options[$ms] = $options[$ann];
		}
	}

	return elgg_get_metastring_based_objects($options, 'annotations');
}


/**
 * Returns entities based upon annotations.  Accepts the same values as
 * elgg_get_entities_from_metadata() but uses the annotations table.
 *
 * NB: Entity creation time is selected as max_time. To sort based upon
 * this, pass 'order_by' => 'maxtime asc' || 'maxtime desc'
 *
 * time_created in this case will be the time the annotation was created.
 *
 * @see elgg_get_entities
 * @see elgg_get_entities_from_metadata
 *
 * @param array $options Array in format:
 *
 * 	annotation_names => NULL|ARR annotations names
 *
 * 	annotation_values => NULL|ARR annotations values
 *
 * 	annotation_name_value_pairs => NULL|ARR (name = 'name', value => 'value',
 * 	'operator' => '=', 'case_sensitive' => TRUE) entries.
 * 	Currently if multiple values are sent via an array (value => array('value1', 'value2')
 * 	the pair's operator will be forced to "IN".
 *
 * 	annotation_name_value_pairs_operator => NULL|STR The operator to use for combining
 *  (name = value) OPERATOR (name = value); default AND
 *
 * 	annotation_case_sensitive => BOOL Overall Case sensitive
 *
 *  order_by_annotation => NULL|ARR (array('name' => 'annotation_text1', 'direction' => ASC|DESC,
 *  'as' => text|integer),
 *
 *  Also supports array('name' => 'annotation_text1')
 *
 *  annotation_owner_guids => NULL|ARR guids for annotaiton owners
 *
 * @return array
 * @since 1.7.0
 */
function elgg_get_entities_from_annotations(array $options = array()) {
	$defaults = array(
		'annotation_names'						=>	ELGG_ENTITIES_ANY_VALUE,
		'annotation_values'						=>	ELGG_ENTITIES_ANY_VALUE,
		'annotation_name_value_pairs'			=>	ELGG_ENTITIES_ANY_VALUE,

		'annotation_name_value_pairs_operator'	=>	'AND',
		'annotation_case_sensitive' 			=>	TRUE,
		'order_by_annotation'					=>	array(),

		'annotation_created_time_lower'			=>	ELGG_ENTITIES_ANY_VALUE,
		'annotation_created_time_upper'			=>	ELGG_ENTITIES_ANY_VALUE,

		'annotation_owner_guids'				=>	ELGG_ENTITIES_ANY_VALUE,

		'order_by'								=>	'maxtime desc',
		'group_by'								=>	'a.entity_guid'
	);

	$options = array_merge($defaults, $options);

	$singulars = array('annotation_name', 'annotation_value',
	'annotation_name_value_pair', 'annotation_owner_guid');

	$options = elgg_normalise_plural_options_array($options, $singulars);

	if (!$options = elgg_entities_get_metastrings_options('annotation', $options)) {
		return FALSE;
	}

	// special sorting for annotations
	//@todo overrides other sorting
	$options['selects'][] = "max(n_table.time_created) as maxtime";
	$options['group_by'] = 'n_table.entity_guid';

	$time_wheres = elgg_get_entity_time_where_sql('a', $options['annotation_created_time_upper'],
		$options['annotation_created_time_lower']);

	if ($time_wheres) {
		$options['wheres'] = array_merge($options['wheres'], $time_wheres);
	}

	return elgg_get_entities_from_metadata($options);
}

/**
 * Returns a viewable list of entities from annotations.
 *
 * @param array $options Options array
 *
 * @see elgg_get_entities_from_annotations()
 * @see elgg_list_entities()
 *
 * @return str
 */
function elgg_list_entities_from_annotations($options = array()) {
	return elgg_list_entities($options, 'elgg_get_entities_from_annotations');
}

/**
 * Returns a rendered list of annotations with pagination.
 *
 * @param array $options Annotation getter and display options.
 * {@see elgg_get_annotations()} and {@see elgg_list_entities()}.
 *
 * @return string The list of entities
 * @since 1.8
 */
function elgg_list_annotations($options) {
	$defaults = array(
		'limit' => 25,
		'offset' => (int) max(get_input('annoff', 0), 0),
	);

	$options = array_merge($defaults, $options);

	return elgg_list_entities($options, 'elgg_get_annotations', 'elgg_view_annotation_list');
}

/**
 * Get entities ordered by a mathematical calculation
 *
 * @param array $options An options array:
 * 	'calculation' => The calculation to use. Must be a valid MySQL function.
 *                   Defaults to sum.  Result selected as 'calculated'.
 *	'order_by'    => The order for the sorting. Defaults to 'calculated desc'.
 *
 * @return mixed
 */
function elgg_get_entities_from_annotation_calculation($options) {
	global $CONFIG;

	$defaults = array(
		'calculation'	=>	'sum',
		'order_by'		=>	'calculated desc',
	);

	$options = array_merge($defaults, $options);

	$function = sanitize_string(elgg_get_array_value('calculation', $options, 'sum', false));

	// you must cast this as an int or it sorts wrong.
	$options['selects'][] = "$function(cast(msv.string as signed)) as calculated";
	$options['selects'][] = "msn.string as value";
	$options['order_by'] = 'calculated desc';

	// need our own join to get the values.
	$db_prefix = get_config('dbprefix');
	$options['joins'][] = "JOIN {$db_prefix}annotations calc_table on e.guid = calc_table.entity_guid";
	$options['joins'][] = "JOIN {$db_prefix}metastrings msv on calc_table.value_id = msv.id";
	$options['wheres'][] = "calc_table.name_id = n_table.name_id";

	return elgg_get_entities_from_annotations($options);
}

/**
 * List entities from an annotation calculation.
 *
 * @see elgg_get_entities_from_annotation_calculation()
 *
 * @param array $options An options array.
 *
 * @return string
 */
function elgg_list_entities_from_annotation_calculation($options) {
	return elgg_list_entities($options, 'elgg_get_entities_from_annotation_calculation');
}


/**
 * Delete a given annotation.
 *
 * @param int $id The annotation id
 *
 * @return bool
 */
function delete_annotation($id) {
	global $CONFIG;

	$id = (int)$id;

	$access = get_access_sql_suffix();
	$annotation = get_annotation($id);

	if (elgg_trigger_event('delete', 'annotation', $annotation)) {
		remove_from_river_by_annotation($id);
		return delete_data("DELETE from {$CONFIG->dbprefix}annotations where id=$id and $access");
	}

	return FALSE;
}

/**
 * Clear all the annotations for a given entity, assuming you have access to that metadata.
 *
 * @param int    $guid The entity guid
 * @param string $name The name of the annotation to delete.
 *
 * @return int Number of annotations deleted or false if an error
 */
function clear_annotations($guid, $name = "") {
	global $CONFIG;

	$guid = (int)$guid;

	if (!empty($name)) {
		$name = get_metastring_id($name);
		if ($name === false) {
			// name doesn't exist so 0 rows were deleted
			return 0;
		}
	}

	$entity_guid = (int) $guid;
	if ($entity = get_entity($entity_guid)) {
		if ($entity->canEdit()) {
			$where = array();

			if ($name != "") {
				$where[] = " name_id='$name'";
			}

			$query = "DELETE from {$CONFIG->dbprefix}annotations where entity_guid=$guid ";
			foreach ($where as $w) {
				$query .= " and $w";
			}

			return delete_data($query);
		}
	}

	return FALSE;
}

/**
 * Clear all annotations belonging to a given owner_guid
 *
 * @param int $owner_guid The owner
 *
 * @return int Number of annotations deleted
 */
function clear_annotations_by_owner($owner_guid) {
	global $CONFIG;

	$owner_guid = (int)$owner_guid;

	$query = "SELECT id from {$CONFIG->dbprefix}annotations WHERE owner_guid=$owner_guid";

	$annotations = get_data($query);
	$deleted = 0;

	if (!$annotations) {
		return 0;
	}

	foreach ($annotations as $id) {
		// Is this the best way?
		if (delete_annotation($id->id)) {
			$deleted++;
		}
	}

	return $deleted;
}

/**
 * Handler called by trigger_plugin_hook on the "export" event.
 *
 * @param string $hook        'export'
 * @param string $entity_type 'all'
 * @param mixed  $returnvalue Default return value
 * @param mixed  $params      List of params to export
 *
 * @elgg_plugin_hook export all
 *
 * @return mixed
 */
function export_annotation_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	// Sanity check values
	if ((!is_array($params)) && (!isset($params['guid']))) {
		throw new InvalidParameterException(elgg_echo('InvalidParameterException:GUIDNotForExport'));
	}

	if (!is_array($returnvalue)) {
		throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonArrayReturnValue'));
	}

	$guid = (int)$params['guid'];
	$name = $params['name'];

	$result = elgg_get_annotations(array(
		'guid' => $guid,
		'limit' => 0
	));

	if ($result) {
		foreach ($result as $r) {
			$returnvalue[] = $r->export();
		}
	}

	return $returnvalue;
}

/**
 * Get the URL for this item of metadata, by default this links to the
 * export handler in the current view.
 *
 * @param int $id Annotation id
 *
 * @return mixed
 */
function get_annotation_url($id) {
	$id = (int)$id;

	if ($extender = get_annotation($id)) {
		return get_extender_url($extender);
	}
	return false;
}

/**
 * Check to see if a user has already created an annotation on an object
 *
 * @param int    $entity_guid     Entity guid
 * @param string $annotation_type Type of annotation
 * @param int    $owner_guid      Defaults to logged in user.
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_annotation_exists($entity_guid, $annotation_type, $owner_guid = NULL) {
	global $CONFIG;

	if (!$owner_guid && !($owner_guid = elgg_get_logged_in_user_guid())) {
		return FALSE;
	}

	$entity_guid = (int)$entity_guid;
	$annotation_type = sanitise_string($annotation_type);

	$sql = "select a.id" .
			" FROM {$CONFIG->dbprefix}annotations a, {$CONFIG->dbprefix}metastrings m " .
			" WHERE a.owner_guid={$owner_guid} AND a.entity_guid={$entity_guid} " .
			" AND a.name_id=m.id AND m.string='{$annotation_type}'";

	if ($check_annotation = get_data_row($sql)) {
		return TRUE;
	}

	return FALSE;
}

/**
 * Register an annotation url handler.
 *
 * @param string $function_name The function.
 * @param string $extender_name The name, default 'all'.
 *
 * @return string
 */
function register_annotation_url_handler($function_name, $extender_name = "all") {
	return register_extender_url_handler($function_name, 'annotation', $extender_name);
}

/** Register the hook */
elgg_register_plugin_hook_handler("export", "all", "export_annotation_plugin_hook", 2);
