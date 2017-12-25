<?php
/**
 * Elgg metadata
 * Functions to manage entity metadata.
 *
 * @package Elgg.Core
 * @subpackage DataModel.Metadata
 */

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
	$metadata = elgg_get_metadata_from_id($id);
	if (!$metadata) {
		return;
	}
	return $metadata->delete();
}

/**
 * Fetch metadata or perform a calculation on them
 *
 * Accepts all options supported by {@link elgg_get_entities()}
 *
 * @see   elgg_get_entities()
 *
 * @param array $options Options
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
 * @codeCoverageIgnore
 */
function _elgg_metadata_test($hook, $type, $value, $params) {
	$value[] = ElggCoreMetadataAPITest::class;
	return $value;
}

/**
 * @see \Elgg\Application\Bootstrap::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$hooks->registerHandler('unit_test', 'system', '_elgg_metadata_test');
};
