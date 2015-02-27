<?php
/**
 * Elgg metadata
 * Functions to manage entity metadata.
 *
 * @package Elgg.Core
 * @subpackage DataModel.Metadata
 */

/**
 * Convert a database row to a new \ElggMetadata
 *
 * @param \stdClass $row An object from the database
 *
 * @return \stdClass|\ElggMetadata
 * @access private
 */
function row_to_elggmetadata($row) {
	if (!($row instanceof \stdClass)) {
		return $row;
	}

	return new \ElggMetadata($row);
}

/**
 * Get a specific metadata object by its id.
 * If you want multiple metadata objects, use
 * {@link elgg_get_metadata()}.
 *
 * @param int $id The id of the metadata object being retrieved.
 *
 * @return \ElggMetadata|false  false if not found
 */
function elgg_get_metadata_from_id($id) {
	return _elgg_services()->metadataTable->get($id);
}

/**
 * Deletes metadata using its ID.
 *
 * @param int $id The metadata ID to delete.
 * @return bool
 */
function elgg_delete_metadata_by_id($id) {
	return _elgg_services()->metadataTable->delete($id);
}

/**
 * Create a new metadata object, or update an existing one.
 *
 * Metadata can be an array by setting allow_multiple to true, but it is an
 * indexed array with no control over the indexing.
 *
 * @param int    $entity_guid    The entity to attach the metadata to
 * @param string $name           Name of the metadata
 * @param string $value          Value of the metadata
 * @param string $value_type     'text', 'integer', or '' for automatic detection
 * @param int    $owner_guid     GUID of entity that owns the metadata. Default is logged in user.
 * @param int    $access_id      Default is ACCESS_PRIVATE
 * @param bool   $allow_multiple Allow multiple values for one key. Default is false
 *
 * @return int|false id of metadata or false if failure
 */
function create_metadata($entity_guid, $name, $value, $value_type = '', $owner_guid = 0,
		$access_id = ACCESS_PRIVATE, $allow_multiple = false) {

	return _elgg_services()->metadataTable->create($entity_guid, $name, $value,
		$value_type, $owner_guid, $access_id, $allow_multiple);
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
	return _elgg_services()->metadataTable->update($id, $name, $value,
		$value_type, $owner_guid, $access_id);
}

/**
 * This function creates metadata from an associative array of "key => value" pairs.
 *
 * To achieve an array for a single key, pass in the same key multiple times with
 * allow_multiple set to true. This creates an indexed array. It does not support
 * associative arrays and there is no guarantee on the ordering in the array.
 *
 * @param int    $entity_guid     The entity to attach the metadata to
 * @param array  $name_and_values Associative array - a value can be a string, number, bool
 * @param string $value_type      'text', 'integer', or '' for automatic detection
 * @param int    $owner_guid      GUID of entity that owns the metadata
 * @param int    $access_id       Default is ACCESS_PRIVATE
 * @param bool   $allow_multiple  Allow multiple values for one key. Default is false
 *
 * @return bool
 */
function create_metadata_from_array($entity_guid, array $name_and_values, $value_type, $owner_guid,
		$access_id = ACCESS_PRIVATE, $allow_multiple = false) {

	return _elgg_services()->metadataTable->createFromArray($entity_guid, $name_and_values,
		$value_type, $owner_guid, $access_id, $allow_multiple);

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
 * metadata_names               => null|ARR metadata names
 * metadata_values              => null|ARR metadata values
 * metadata_ids                 => null|ARR metadata ids
 * metadata_case_sensitive      => BOOL Overall Case sensitive
 * metadata_owner_guids         => null|ARR guids for metadata owners
 * metadata_created_time_lower  => INT Lower limit for created time.
 * metadata_created_time_upper  => INT Upper limit for created time.
 * metadata_calculation         => STR Perform the MySQL function on the metadata values returned.
 *                                   The "metadata_calculation" option causes this function to
 *                                   return the result of performing a mathematical calculation on
 *                                   all metadata that match the query instead of returning
 *                                   \ElggMetadata objects.
 *
 * @return \ElggMetadata[]|mixed
 * @since 1.8.0
 */
function elgg_get_metadata(array $options = array()) {
	return _elgg_services()->metadataTable->getAll($options);
}

/**
 * Deletes metadata based on $options.
 *
 * @warning Unlike elgg_get_metadata() this will not accept an empty options array!
 *          This requires at least one constraint: metadata_owner_guid(s),
 *          metadata_name(s), metadata_value(s), or guid(s) must be set.
 *
 * @param array $options An options array. {@link elgg_get_metadata()}
 * @return bool|null true on success, false on failure, null if no metadata to delete.
 * @since 1.8.0
 */
function elgg_delete_metadata(array $options) {
	return _elgg_services()->metadataTable->deleteAll($options);
}

/**
 * Disables metadata based on $options.
 *
 * @warning Unlike elgg_get_metadata() this will not accept an empty options array!
 *
 * @param array $options An options array. {@link elgg_get_metadata()}
 * @return bool|null true on success, false on failure, null if no metadata disabled.
 * @since 1.8.0
 */
function elgg_disable_metadata(array $options) {
	return _elgg_services()->metadataTable->disableAll($options);
}

/**
 * Enables metadata based on $options.
 *
 * @warning Unlike elgg_get_metadata() this will not accept an empty options array!
 *
 * @warning In order to enable metadata, you must first use
 * {@link access_show_hidden_entities()}.
 *
 * @param array $options An options array. {@link elgg_get_metadata()}
 * @return bool|null true on success, false on failure, null if no metadata enabled.
 * @since 1.8.0
 */
function elgg_enable_metadata(array $options) {
	return _elgg_services()->metadataTable->enableAll($options);
}

/**
 * \ElggEntities interfaces
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
 * 	metadata_names => null|ARR metadata names
 *
 * 	metadata_values => null|ARR metadata values
 *
 * 	metadata_name_value_pairs => null|ARR (
 *                                         name => 'name',
 *                                         value => 'value',
 *                                         'operand' => '=',
 *                                         'case_sensitive' => true
 *                                        )
 *                               Currently if multiple values are sent via
 *                               an array (value => array('value1', 'value2')
 *                               the pair's operand will be forced to "IN".
 *                               If passing "IN" as the operand and a string as the value, 
 *                               the value must be a properly quoted and escaped string.
 *
 * 	metadata_name_value_pairs_operator => null|STR The operator to use for combining
 *                                        (name = value) OPERATOR (name = value); default AND
 *
 * 	metadata_case_sensitive => BOOL Overall Case sensitive
 *
 *  order_by_metadata => null|ARR array(
 *                                      'name' => 'metadata_text1',
 *                                      'direction' => ASC|DESC,
 *                                      'as' => text|integer
 *                                     )
 *                                Also supports array('name' => 'metadata_text1')
 *
 *  metadata_owner_guids => null|ARR guids for metadata owners
 *
 * @return \ElggEntity[]|mixed If count, int. If not count, array. false on errors.
 * @since 1.7.0
 */
function elgg_get_entities_from_metadata(array $options = array()) {
	return _elgg_services()->metadataTable->getEntities($options);
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
function _elgg_get_entity_metadata_where_sql($e_table, $n_table, $names = null, $values = null,
		$pairs = null, $pair_operator = 'AND', $case_sensitive = true, $order_by_metadata = null,
		$owner_guids = null) {
	return _elgg_services()->metadataTable->getEntityMetadataWhereSql($e_table, $n_table, $names,
		$values, $pairs, $pair_operator, $case_sensitive, $order_by_metadata, $owner_guids);
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
	return _elgg_services()->metadataTable->getUrl($id);
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
	_elgg_services()->metadataTable->registerMetadataAsIndependent($type, $subtype);
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
	return _elgg_services()->metadataTable->isMetadataIndependent($type, $subtype);
}

/**
 * When an entity is updated, resets the access ID on all of its child metadata
 *
 * @param string     $event       The name of the event
 * @param string     $object_type The type of object
 * @param \ElggEntity $object      The entity itself
 *
 * @return true
 * @access private Set as private in 1.9.0
 */
function metadata_update($event, $object_type, $object) {
	return _elgg_services()->metadataTable->handleUpdate($event, $object_type, $object);
}

/**
 * Invalidate the metadata cache based on options passed to various *_metadata functions
 *
 * @param string $action  Action performed on metadata. "delete", "disable", or "enable"
 * @param array  $options Options passed to elgg_(delete|disable|enable)_metadata
 * @return void
 * @access private
 * @todo not used
 */
function _elgg_invalidate_metadata_cache($action, array $options) {
	_elgg_services()->metadataCache->invalidateByOptions($options);
}

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
function _elgg_metadata_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = $CONFIG->path . 'engine/tests/ElggCoreMetadataAPITest.php';
	$value[] = $CONFIG->path . 'engine/tests/ElggCoreMetadataCacheTest.php';
	return $value;
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	/** Call a function whenever an entity is updated **/
	$events->registerHandler('update', 'all', 'metadata_update');

	$hooks->registerHandler('unit_test', 'system', '_elgg_metadata_test');
};
