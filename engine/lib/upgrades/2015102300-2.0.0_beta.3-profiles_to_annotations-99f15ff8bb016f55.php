<?php
/**
 * Elgg 2.0.0-beta.3 upgrade 2015102300
 * profiles_to_annotations
 *
 * Description
 */

// upgrade code here.
$names = array_keys(elgg_get_config('profile_fields'));
$prefix = elgg_get_config('dbprefix');

foreach ($names as $name) {
	$metadata_name_id = elgg_get_metastring_id($name);
	$annotation_name_id = elgg_get_metastring_id("profile:$name");

	update_data("
		INSERT INTO {$prefix}annotations
			(entity_guid, name_id,               value_id, value_type, owner_guid, access_id, time_created, enabled)
		SELECT entity_guid, {$annotation_name_id}, value_id, value_type, owner_guid, access_id, time_created, enabled
		FROM {$prefix}metadata
		WHERE name_id = {$metadata_name_id}
		AND entity_guid IN (
			SELECT guid FROM {$prefix}users_entity
		)
	");
}
