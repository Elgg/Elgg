<?php
/**
 * Elgg metadata
 * Functions to manage entity metadata.
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
		return false;
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
 * Get popular tags and their frequencies
 *
 * Accepts all options supported by {@see elgg_get_metadata()}
 *
 * Returns an array of objects that include "tag" and "total" properties
 *
 * @param array $options Options
 *
 * @option int      $threshold Minimum number of tag occurrences
 * @option string[] $tag_names Names of tag names to include in search
 *
 * @return stdClass[]|false
 * @since 1.7.1
 */
function elgg_get_tags(array $options = []) {
	return _elgg_services()->metadataTable->getTags($options);
}
