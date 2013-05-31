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
 * @access private
 */
function row_to_elggannotation($row) {
	if (!($row instanceof stdClass)) {
		// @todo should throw in this case?
		return $row;
	}

	return new ElggAnnotation($row);
}

/**
 * Get a specific annotation by its id.
 * If you want multiple annotation objects, use
 * {@link elgg_get_annotations()}.
 *
 * @param int $id The id of the annotation object being retrieved.
 *
 * @return ElggAnnotation|false
 */
function elgg_get_annotation_from_id($id) {
	return elgg_get_metastring_based_object_from_id($id, 'annotations');
}

/**
 * Deletes an annotation using its ID.
 *
 * @param int $id The annotation ID to delete.
 * @return bool
 */
function elgg_delete_annotation_by_id($id) {
	$annotation = elgg_get_annotation_from_id($id);
	if (!$annotation) {
		return false;
	}
	return $annotation->delete();
}

/**
 * Create a new annotation.
 *
 * @param int    $entity_guid Entity Guid
 * @param string $name        Name of annotation
 * @param string $value       Value of annotation
 * @param string $value_type  Type of value (default is auto detection)
 * @param int    $owner_guid  Owner of annotation (default is logged in user)
 * @param int    $access_id   Access level of annotation
 *
 * @return int|bool id on success or false on failure
 */
function create_annotation($entity_guid, $name, $value, $value_type = '',
$owner_guid = 0, $access_id = ACCESS_PRIVATE) {
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
		// If ok then add it
		$result = insert_data("INSERT into {$CONFIG->dbprefix}annotations
			(entity_guid, name_id, value_id, value_type, owner_guid, time_created, access_id) VALUES
			($entity_guid,'$name',$value,'$value_type', $owner_guid, $time, $access_id)");

		if ($result !== false) {
			$obj = elgg_get_annotation_from_id($result);
			if (elgg_trigger_event('create', 'annotation', $obj)) {
				return $result;
			} else {
				// plugin returned false to reject annotation
				elgg_delete_annotation_by_id($result);
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
		set name_id='$name', value_id='$value', value_type='$value_type', access_id=$access_id, owner_guid=$owner_guid
		where id=$annotation_id and $access");

	if ($result !== false) {
		// @todo add plugin hook that sends old and new annotation information before db access
		$obj = elgg_get_annotation_from_id($annotation_id);
		elgg_trigger_event('update', 'annotation', $obj);
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
 * annotation_names              => NULL|ARR Annotation names
 * annotation_values             => NULL|ARR Annotation values
 * annotation_ids                => NULL|ARR annotation ids
 * annotation_case_sensitive     => BOOL Overall Case sensitive
 * annotation_owner_guids        => NULL|ARR guids for annotation owners
 * annotation_created_time_lower => INT Lower limit for created time.
 * annotation_created_time_upper => INT Upper limit for created time.
 * annotation_calculation        => STR Perform the MySQL function on the annotation values returned.
 *                                   Do not confuse this "annotation_calculation" option with the
 *                                   "calculation" option to elgg_get_entities_from_annotation_calculation().
 *                                   The "annotation_calculation" option causes this function to
 *                                   return the result of performing a mathematical calculation on
 *                                   all annotations that match the query instead of ElggAnnotation
 *                                   objects.
 *                                   See the docs for elgg_get_entities_from_annotation_calculation()
 *                                   for the proper use of the "calculation" option.
 *
 *
 * @return ElggAnnotation[]|mixed
 * @since 1.8.0
 */
function elgg_get_annotations(array $options = array()) {

	// @todo remove support for count shortcut - see #4393
	if (isset($options['__egefac']) && $options['__egefac']) {
		unset($options['__egefac']);
	} else {
		// support shortcut of 'count' => true for 'annotation_calculation' => 'count'
		if (isset($options['count']) && $options['count']) {
			$options['annotation_calculation'] = 'count';
			unset($options['count']);
		}		
	}
	
	$options['metastring_type'] = 'annotations';
	return elgg_get_metastring_based_objects($options);
}

/**
 * Deletes annotations based on $options.
 *
 * @warning Unlike elgg_get_annotations() this will not accept an empty options array!
 *          This requires at least one constraint: annotation_owner_guid(s),
 *          annotation_name(s), annotation_value(s), or guid(s) must be set.
 *
 * @param array $options An options array. {@See elgg_get_annotations()}
 * @return bool|null true on success, false on failure, null if no annotations to delete.
 * @since 1.8.0
 */
function elgg_delete_annotations(array $options) {
	if (!elgg_is_valid_options_for_batch_operation($options, 'annotations')) {
		return false;
	}

	$options['metastring_type'] = 'annotations';
	return elgg_batch_metastring_based_objects($options, 'elgg_batch_delete_callback', false);
}

/**
 * Disables annotations based on $options.
 *
 * @warning Unlike elgg_get_annotations() this will not accept an empty options array!
 *
 * @param array $options An options array. {@See elgg_get_annotations()}
 * @return bool|null true on success, false on failure, null if no annotations disabled.
 * @since 1.8.0
 */
function elgg_disable_annotations(array $options) {
	if (!elgg_is_valid_options_for_batch_operation($options, 'annotations')) {
		return false;
	}

	$options['metastring_type'] = 'annotations';
	return elgg_batch_metastring_based_objects($options, 'elgg_batch_disable_callback', false);
}

/**
 * Enables annotations based on $options.
 *
 * @warning Unlike elgg_get_annotations() this will not accept an empty options array!
 *
 * @warning In order to enable annotations, you must first use
 * {@link access_show_hidden_entities()}.
 *
 * @param array $options An options array. {@See elgg_get_annotations()}
 * @return bool|null true on success, false on failure, null if no metadata enabled.
 * @since 1.8.0
 */
function elgg_enable_annotations(array $options) {
	if (!$options || !is_array($options)) {
		return false;
	}

	$options['metastring_type'] = 'annotations';
	return elgg_batch_metastring_based_objects($options, 'elgg_batch_enable_callback');
}

/**
 * Returns a rendered list of annotations with pagination.
 *
 * @param array $options Annotation getter and display options.
 * {@see elgg_get_annotations()} and {@see elgg_list_entities()}.
 *
 * @return string The list of entities
 * @since 1.8.0
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
 * Entities interfaces
 */

/**
 * Returns entities based upon annotations.  Also accepts all options available
 * to elgg_get_entities() and elgg_get_entities_from_metadata().
 *
 * Entity creation time is selected as maxtime. To sort based upon
 * this, pass 'order_by' => 'maxtime asc' || 'maxtime desc'
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
 * @return mixed If count, int. If not count, array. false on errors.
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
	$options = elgg_entities_get_metastrings_options('annotation', $options);

	if (!$options) {
		return false;
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
 * @return string
 */
function elgg_list_entities_from_annotations($options = array()) {
	return elgg_list_entities($options, 'elgg_get_entities_from_annotations');
}

/**
 * Get entities ordered by a mathematical calculation on annotation values
 *
 * @param array $options An options array:
 * 	'calculation'            => The calculation to use. Must be a valid MySQL function.
 *                              Defaults to sum.  Result selected as 'annotation_calculation'.
 *                              Don't confuse this "calculation" option with the
 *                              "annotation_calculation" option to elgg_get_annotations().
 *                              This "calculation" option is applied to each entity's set of
 *                              annotations and is selected as annotation_calculation for that row.
 *                              See the docs for elgg_get_annotations() for proper use of the
 *                              "annotation_calculation" option.
 *	'order_by'               => The order for the sorting. Defaults to 'annotation_calculation desc'.
 *	'annotation_names'       => The names of annotations on the entity.
 *	'annotation_values'	     => The values of annotations on the entity.
 *
 *	'metadata_names'         => The name of metadata on the entity.
 *	'metadata_values'        => The value of metadata on the entitiy.
 *
 * @return mixed If count, int. If not count, array. false on errors.
 */
function elgg_get_entities_from_annotation_calculation($options) {
	$db_prefix = elgg_get_config('dbprefix');
	$defaults = array(
		'calculation' => 'sum',
		'order_by' => 'annotation_calculation desc'
	);

	$options = array_merge($defaults, $options);

	$function = sanitize_string(elgg_extract('calculation', $options, 'sum', false));

	// you must cast this as an int or it sorts wrong.
	$options['selects'][] = 'e.*';
	$options['selects'][] = "$function(cast(a_msv.string as signed)) as annotation_calculation";

	// need our own join to get the values because the lower level functions don't
	// add all the joins if it's a different callback.
	$options['joins'][] = "JOIN {$db_prefix}metastrings a_msv ON n_table.value_id = a_msv.id";

	// don't need access control because it's taken care of by elgg_get_annotations.
	$options['group_by'] = 'n_table.entity_guid';

	$options['callback'] = 'entity_row_to_elggstar';

	// see #4393
	// @todo remove after the 'count' shortcut is removed from elgg_get_annotations()
	$options['__egefac'] = true;

	return elgg_get_annotations($options);
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
	$defaults = array(
		'calculation' => 'sum',
		'order_by' => 'annotation_calculation desc'
	);
	$options = array_merge($defaults, $options);

	return elgg_list_entities($options, 'elgg_get_entities_from_annotation_calculation');
}

/**
 * Export the annotations for the specified entity
 *
 * @param string $hook        'export'
 * @param string $type        'all'
 * @param mixed  $returnvalue Default return value
 * @param mixed  $params      Parameters determining what annotations to export
 *
 * @elgg_plugin_hook export all
 *
 * @return array
 * @throws InvalidParameterException
 * @access private
 */
function export_annotation_plugin_hook($hook, $type, $returnvalue, $params) {
	// Sanity check values
	if ((!is_array($params)) && (!isset($params['guid']))) {
		throw new InvalidParameterException(elgg_echo('InvalidParameterException:GUIDNotForExport'));
	}

	if (!is_array($returnvalue)) {
		throw new InvalidParameterException(elgg_echo('InvalidParameterException:NonArrayReturnValue'));
	}

	$guid = (int)$params['guid'];
	$options = array('guid' => $guid, 'limit' => 0);
	if (isset($params['name'])) {
		$options['annotation_name'] = $params['name'];
	}

	$result = elgg_get_annotations($options);

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

	if ($extender = elgg_get_annotation_from_id($id)) {
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

	$entity_guid = sanitize_int($entity_guid);
	$owner_guid = sanitize_int($owner_guid);
	$annotation_type = sanitize_string($annotation_type);

	$sql = "SELECT a.id FROM {$CONFIG->dbprefix}annotations a" .
			" JOIN {$CONFIG->dbprefix}metastrings m ON a.name_id = m.id" .
			" WHERE a.owner_guid = $owner_guid AND a.entity_guid = $entity_guid" .
			" AND m.string = '$annotation_type'";

	if (get_data_row($sql)) {
		return TRUE;
	}

	return FALSE;
}

/**
 * Return the URL for a comment
 *
 * @param ElggAnnotation $comment The comment object 
 * @return string
 * @access private
 */
function elgg_comment_url_handler(ElggAnnotation $comment) {
	$entity = $comment->getEntity();
	if ($entity) {
		return $entity->getURL() . '#item-annotation-' . $comment->id;
	}
	return "";
}

/**
 * Register an annotation url handler.
 *
 * @param string $extender_name The name, default 'all'.
 * @param string $function_name The function.
 *
 * @return string
 */
function elgg_register_annotation_url_handler($extender_name = "all", $function_name) {
	return elgg_register_extender_url_handler('annotation', $extender_name, $function_name);
}

/**
 * Register annotation unit tests
 *
 * @param string $hook
 * @param string $type
 * @param array $value
 * @param array $params
 * @return array
 * @access private
 */
function annotations_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = $CONFIG->path . 'engine/tests/api/annotations.php';
	return $value;
}

/**
 * Initialize the annotation library
 * @access private
 */
function elgg_annotations_init() {
	elgg_register_annotation_url_handler('generic_comment', 'elgg_comment_url_handler');

	elgg_register_plugin_hook_handler("export", "all", "export_annotation_plugin_hook", 2);
	elgg_register_plugin_hook_handler('unit_test', 'system', 'annotations_test');
}

elgg_register_event_handler('init', 'system', 'elgg_annotations_init');
