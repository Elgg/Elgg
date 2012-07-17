<?php
/**
 * Elgg Data import export functionality.
 *
 * @package Elgg.Core
 * @subpackage DataModel.Export
 */

/**
 * Get a UUID from a given object.
 *
 * @param mixed $object The object either an ElggEntity, ElggRelationship or ElggExtender
 *
 * @return the UUID or false
 */
function get_uuid_from_object($object) {
	if ($object instanceof ElggEntity) {
		return guid_to_uuid($object->guid);
	} else if ($object instanceof ElggExtender) {
		$type = $object->type;
		if ($type == 'volatile') {
			$uuid = guid_to_uuid($object->entity_guid) . $type . "/{$object->name}/";
		} else {
			$uuid = guid_to_uuid($object->entity_guid) . $type . "/{$object->id}/";
		}

		return $uuid;
	} else if ($object instanceof ElggRelationship) {
		return guid_to_uuid($object->guid_one) . "relationship/{$object->id}/";
	}

	return false;
}

/**
 * Generate a UUID from a given GUID.
 *
 * @param int $guid The GUID of an object.
 *
 * @return string
 */
function guid_to_uuid($guid) {
	global $CONFIG;

	return elgg_get_site_url()  . "export/opendd/$guid/";
}

/**
 * Test to see if a given uuid is for this domain, returning true if so.
 *
 * @param string $uuid A unique ID
 *
 * @return bool
 */
function is_uuid_this_domain($uuid) {
	global $CONFIG;

	if (strpos($uuid, elgg_get_site_url()) === 0) {
		return true;
	}

	return false;
}

/**
 * This function attempts to retrieve a previously imported entity via its UUID.
 *
 * @param string $uuid A unique ID
 *
 * @return mixed
 */
function get_entity_from_uuid($uuid) {
	$uuid = sanitise_string($uuid);

	$options = array('metadata_name' => 'import_uuid', 'metadata_value' => $uuid);
	$entities = elgg_get_entities_from_metadata($options);

	if ($entities) {
		return $entities[0];
	}

	return false;
}

/**
 * Tag a previously created guid with the uuid it was imported on.
 *
 * @param int    $guid A GUID
 * @param string $uuid A Unique ID
 *
 * @return bool
 */
function add_uuid_to_guid($guid, $uuid) {
	$guid = (int)$guid;
	$uuid = sanitise_string($uuid);

	$result = create_metadata($guid, "import_uuid", $uuid);
	return (bool)$result;
}

/**
 * Exports an entity as an array
 *
 * @param int $guid Entity GUID
 *
 * @return array
 * @throws ExportException
 * @access private
 */
function exportAsArray($guid) {
	$guid = (int)$guid;

	// Trigger a hook to
	$to_be_serialised = elgg_trigger_plugin_hook("export", "all", array("guid" => $guid), array());

	// Sanity check
	if ((!is_array($to_be_serialised)) || (count($to_be_serialised) == 0)) {
		throw new ExportException(elgg_echo('ExportException:NoSuchEntity', array($guid)));
	}

	return $to_be_serialised;
}
