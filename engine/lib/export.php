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


$IMPORTED_DATA = array();
$IMPORTED_OBJECT_COUNTER = 0;

/**
 * This function processes an element, passing elements to the plugin stack to see if someone will
 * process it.
 *
 * If nobody processes the top level element, the sub level elements are processed.
 *
 * @param ODD $odd The odd element to process
 *
 * @return bool
 */
function _process_element(ODD $odd) {
	global $IMPORTED_DATA, $IMPORTED_OBJECT_COUNTER;

	// See if anyone handles this element, return true if it is.
	if ($odd) {
		$handled = elgg_trigger_plugin_hook("import", "all", array("element" => $odd), $to_be_serialised);
	}

	// If not, then see if any of its sub elements are handled
	if ($handled) {
		// Increment validation counter
		$IMPORTED_OBJECT_COUNTER ++;
		// Return the constructed object
		$IMPORTED_DATA[] = $handled;

		return true;
	}

	return false;
}

/**
 * Exports an entity as an array
 *
 * @param int $guid Entity GUID
 *
 * @return array
 * @throws ExportException
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

/**
 * Export a GUID.
 *
 * This function exports a GUID and all information related to it in an XML format.
 *
 * This function makes use of the "serialise" plugin hook, which is passed an array to which plugins
 * should add data to be serialised to.
 *
 * @param int $guid The GUID.
 *
 * @return xml
 * @see ElggEntity for an example of its usage.
 */
function export($guid) {
	$odd = new ODDDocument(exportAsArray($guid));

	return ODD_Export($odd);
}

/**
 * Import an XML serialisation of an object.
 * This will make a best attempt at importing a given xml doc.
 *
 * @param string $xml XML string
 *
 * @return bool
 * @throws Exception if there was a problem importing the data.
 */
function import($xml) {
	global $IMPORTED_DATA, $IMPORTED_OBJECT_COUNTER;

	$IMPORTED_DATA = array();
	$IMPORTED_OBJECT_COUNTER = 0;

	$document = ODD_Import($xml);
	if (!$document) {
		throw new ImportException(elgg_echo('ImportException:NoODDElements'));
	}

	foreach ($document as $element) {
		_process_element($element);
	}

	if ($IMPORTED_OBJECT_COUNTER != count($IMPORTED_DATA)) {
		throw new ImportException(elgg_echo('ImportException:NotAllImported'));
	}

	return true;
}


/**
 * Register the OpenDD import action
 *
 * @return void
 */
function export_init() {
	global $CONFIG;

	elgg_register_action("import/opendd");
}

// Register a startup event
elgg_register_event_handler('init', 'system', 'export_init', 100);
