<?php
/**
 * Elgg annotations
 * Functions to manage object annotations.
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Convert a database row to a new \ElggAnnotation
 *
 * @param \stdClass $row Db row result object
 *
 * @return \ElggAnnotation
 * @access private
 */
function row_to_elggannotation($row) {
	if (!($row instanceof \stdClass)) {
		// @todo should throw in this case?
		return $row;
	}

	return new \ElggAnnotation($row);
}

/**
 * Get a specific annotation by its id.
 * If you want multiple annotation objects, use
 * {@link elgg_get_annotations()}.
 *
 * @param int $id The id of the annotation object being retrieved.
 *
 * @return \ElggAnnotation|false
 */
function elgg_get_annotation_from_id($id) {
	return _elgg_services()->annotations->get($id);
}

/**
 * Deletes an annotation using its ID.
 *
 * @param int $id The annotation ID to delete.
 * @return bool
 */
function elgg_delete_annotation_by_id($id) {
	return _elgg_services()->annotations->delete($id);
}

/**
 * Create a new annotation.
 *
 * @param int    $entity_guid GUID of entity to be annotated
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
	return _elgg_services()->annotations->create(
		$entity_guid, $name, $value, $value_type, $owner_guid, $access_id);
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
	return _elgg_services()->annotations->update($annotation_id, $name, $value, $value_type, $owner_guid, $access_id);
}

/**
 * Returns annotations.  Accepts all elgg_get_entities() options for entity
 * restraints.
 *
 * @see elgg_get_entities
 *
 * @param array $options Array in format:
 *
 * annotation_names              => null|ARR Annotation names
 * annotation_values             => null|ARR Annotation values
 * annotation_ids                => null|ARR annotation ids
 * annotation_case_sensitive     => BOOL Overall Case sensitive
 * annotation_owner_guids        => null|ARR guids for annotation owners
 * annotation_created_time_lower => INT Lower limit for created time.
 * annotation_created_time_upper => INT Upper limit for created time.
 * annotation_calculation        => STR Perform the MySQL function on the annotation values returned.
 *                                   Do not confuse this "annotation_calculation" option with the
 *                                   "calculation" option to elgg_get_entities_from_annotation_calculation().
 *                                   The "annotation_calculation" option causes this function to
 *                                   return the result of performing a mathematical calculation on
 *                                   all annotations that match the query instead of \ElggAnnotation
 *                                   objects.
 *                                   See the docs for elgg_get_entities_from_annotation_calculation()
 *                                   for the proper use of the "calculation" option.
 *
 *
 * @return \ElggAnnotation[]|mixed
 * @since 1.8.0
 */
function elgg_get_annotations(array $options = array()) {
	return _elgg_services()->annotations->find($options);
}

/**
 * Returns a rendered list of annotations with pagination.
 *
 * @param array $options Annotation getter and display options.
 * {@link elgg_get_annotations()} and {@link elgg_list_entities()}.
 *
 * @return string The list of entities
 * @since 1.8.0
 */
function elgg_list_annotations($options) {
	$defaults = array(
		'limit' => 25,
		'offset' => (int) max(get_input('annoff', 0), 0),
		'no_results' => '',
	);

	$options = array_merge($defaults, $options);

	return elgg_list_entities($options, 'elgg_get_annotations', 'elgg_view_annotation_list');
}

/**
 * Deletes annotations based on $options.
 *
 * @warning Unlike elgg_get_annotations() this will not accept an empty options array!
 *          This requires at least one constraint: annotation_owner_guid(s),
 *          annotation_name(s), annotation_value(s), or guid(s) must be set.
 *
 * @param array $options An options array. {@link elgg_get_annotations()}
 * @return bool|null true on success, false on failure, null if no annotations to delete.
 * @since 1.8.0
 */
function elgg_delete_annotations(array $options) {
	return _elgg_services()->annotations->deleteAll($options);
}

/**
 * Disables annotations based on $options.
 *
 * @warning Unlike elgg_get_annotations() this will not accept an empty options array!
 *
 * @param array $options An options array. {@link elgg_get_annotations()}
 * @return bool|null true on success, false on failure, null if no annotations disabled.
 * @since 1.8.0
 */
function elgg_disable_annotations(array $options) {
	return _elgg_services()->annotations->disableAll($options);
}

/**
 * Enables annotations based on $options.
 *
 * @warning Unlike elgg_get_annotations() this will not accept an empty options array!
 *
 * @warning In order to enable annotations, you must first use
 * {@link access_show_hidden_entities()}.
 *
 * @param array $options An options array. {@link elgg_get_annotations()}
 * @return bool|null true on success, false on failure, null if no metadata enabled.
 * @since 1.8.0
 */
function elgg_enable_annotations(array $options) {
	return _elgg_services()->annotations->enableAll($options);
}

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
 * 	annotation_names => null|ARR annotations names
 *
 * 	annotation_values => null|ARR annotations values
 *
 * 	annotation_name_value_pairs => null|ARR (name = 'name', value => 'value',
 * 	'operator' => '=', 'case_sensitive' => true) entries.
 * 	Currently if multiple values are sent via an array (value => array('value1', 'value2')
 * 	the pair's operator will be forced to "IN".
 *
 * 	annotation_name_value_pairs_operator => null|STR The operator to use for combining
 *  (name = value) OPERATOR (name = value); default AND
 *
 * 	annotation_case_sensitive => BOOL Overall Case sensitive
 *
 *  order_by_annotation => null|ARR (array('name' => 'annotation_text1', 'direction' => ASC|DESC,
 *  'as' => text|integer),
 *
 *  Also supports array('name' => 'annotation_text1')
 *
 *  annotation_owner_guids => null|ARR guids for annotaiton owners
 *
 * @return mixed If count, int. If not count, array. false on errors.
 * @since 1.7.0
 */
function elgg_get_entities_from_annotations(array $options = array()) {
	return _elgg_services()->annotations->getEntities($options);
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
 * @tip Note that this function uses { @link elgg_get_annotations() } to return a list of entities ordered by a mathematical
 * calculation on annotation values, and { @link elgg_get_entities_from_annotations() } to return a count of entities 
 * if $options['count'] is set to a truthy value
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
 *	'callback'               => Callback function to pass each row through.
 *                              @tip This function is different from other ege* functions, 
 *                              as it uses a metastring-based getter function { @link elgg_get_annotations() },
 *                              therefore the callback function should be a derivative of { @link entity_row_to_elggstar() } 
 *                              and not of { @link row_to_annotation() }
 *
 * @return \ElggEntity[]|int An array or a count of entities
 * @see elgg_get_annotations()
 * @see elgg_get_entities_from_annotations()
 */
function elgg_get_entities_from_annotation_calculation($options) {
	return _elgg_services()->annotations->getEntitiesFromCalculation($options);
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
 * Check to see if a user has already created an annotation on an object
 *
 * @param int    $entity_guid     Entity guid
 * @param string $annotation_type Type of annotation
 * @param int    $owner_guid      Defaults to logged in user.
 *
 * @return bool
 * @since 1.8.0
 */
function elgg_annotation_exists($entity_guid, $annotation_type, $owner_guid = null) {
	return _elgg_services()->annotations->exists($entity_guid, $annotation_type, $owner_guid);
}

/**
 * Set the URL for a comment when called from a plugin hook
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param string $url    URL string
 * @param array  $params Parameters of the hook
 * @return string
 * @access private
 */
function _elgg_set_comment_url($hook, $type, $url, $params) {
	$annotation = $params['extender'];
	/* @var \ElggExtender $annotation */
	if ($annotation->getSubtype() == 'generic_comment') {
		$entity = $annotation->getEntity();
		if ($entity) {
			return $entity->getURL() . '#item-annotation-' . $annotation->id;
		}
	}
}

/**
 * Register annotation unit tests
 *
 * @param string $hook
 * @param string $type
 * @param array  $tests
 * @return array
 * @access private
 */
function _elgg_annotations_test($hook, $type, $tests) {
	global $CONFIG;
	$tests[] = $CONFIG->path . 'engine/tests/ElggCoreAnnotationAPITest.php';
	$tests[] = $CONFIG->path . 'engine/tests/ElggAnnotationTest.php';
	return $tests;
}

/**
 * Initialize the annotation library
 * @access private
 */
function _elgg_annotations_init() {
	elgg_register_plugin_hook_handler('extender:url', 'annotation', '_elgg_set_comment_url');
	elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_annotations_test');
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_annotations_init');
};
