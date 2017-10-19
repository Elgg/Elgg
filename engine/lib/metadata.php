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
 * @param int    $ignored1       This argument is not used
 * @param null   $ignored2       This argument is not used
 * @param bool   $allow_multiple Allow multiple values for one key. Default is false
 *
 * @return int|false id of metadata or false if failure
 */
function create_metadata($entity_guid, $name, $value, $value_type = '', $ignored1 = null,
		$ignored2 = null, $allow_multiple = false) {
	return _elgg_services()->metadataTable->create($entity_guid, $name, $value,
		$value_type, $allow_multiple);
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
function elgg_get_metadata(array $options = []) {
	return _elgg_services()->metadataTable->getAll($options);
}

/**
 * Deletes metadata based on $options.
 *
 * @warning Unlike elgg_get_metadata() this will not accept an empty options array!
 *          This requires at least one constraint:
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
 *				AND md.name = $name
 *				AND md.value = $value)";
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
 * @return \ElggEntity[]|mixed If count, int. If not count, array. false on errors.
 * @since 1.7.0
 */
function elgg_get_entities_from_metadata(array $options = []) {
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
 *
 * @return false|array False on fail, array('joins', 'wheres')
 * @since 1.7.0
 * @access private
 */
function _elgg_get_entity_metadata_where_sql($e_table, $n_table, $names = null, $values = null,
		$pairs = null, $pair_operator = 'AND', $case_sensitive = true, $order_by_metadata = null
		) {
	return _elgg_services()->metadataTable->getEntityMetadataWhereSql($e_table, $n_table, $names,
		$values, $pairs, $pair_operator, $case_sensitive, $order_by_metadata);
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
	$valuearray = [];

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
	$value[] = ElggCoreMetadataAPITest::class;
	$value[] = ElggCoreMetadataCacheTest::class;
	return $value;
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$hooks->registerHandler('unit_test', 'system', '_elgg_metadata_test');
};
