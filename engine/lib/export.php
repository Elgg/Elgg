<?php
/**
 * Elgg Data import export functionality.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

/**
 * Define an interface for all ODD exportable objects.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 */
interface Exportable {
	/**
	 * This must take the contents of the object and convert it to exportable ODD
	 * @return object or array of objects.
	 */
	public function export();

	/**
	 * Return a list of all fields that can be exported.
	 * This should be used as the basis for the values returned by export()
	 */
	public function getExportableValues();
}

/**
 * Define an interface for all ODD importable objects.
 * @author Curverider Ltd
 */
interface Importable {
	/**
	 * Accepts an array of data to import, this data is parsed from the XML produced by export.
	 * The function should return the constructed object data, or NULL.
	 *
	 * @param ODD $data
	 * @return bool
	 * @throws ImportException if there was a critical error importing data.
	 */
	public function import(ODD $data);
}

/**
 * Export exception
 *
 * @package Elgg
 * @subpackage Exceptions
 *
 */
class ExportException extends DataFormatException {}

/**
 * Import exception
 *
 * @package Elgg
 * @subpackage Exceptions
 */
class ImportException extends DataFormatException {}

/**
 * Get a UUID from a given object.
 *
 * @param $object The object either an ElggEntity, ElggRelationship or ElggExtender
 * @return the UUID or false
 */
function get_uuid_from_object($object) {
	if ($object instanceof ElggEntity) {
		return guid_to_uuid($object->guid);
	} else if ($object instanceof ElggExtender) {
		$type = $object->type;
		if ($type == 'volatile') {
			$uuid = guid_to_uuid($object->entity_guid). $type . "/{$object->name}/";
		} else {
			$uuid = guid_to_uuid($object->entity_guid). $type . "/{$object->id}/";
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
 */
function guid_to_uuid($guid) {
	global $CONFIG;

	return $CONFIG->wwwroot  . "export/opendd/$guid/";
}

/**
 * Test to see if a given uuid is for this domain, returning true if so.
 * @param $uuid
 * @return bool
 */
function is_uuid_this_domain($uuid) {
	global $CONFIG;

	if (strpos($uuid, $CONFIG->wwwroot) === 0) {
		return true;
	}

	return false;
}

/**
 * This function attempts to retrieve a previously imported entity via its UUID.
 *
 * @param $uuid
 */
function get_entity_from_uuid($uuid) {
	$uuid = sanitise_string($uuid);

	$entities = elgg_get_entities_from_metadata(array('metadata_name' => 'import_uuid', 'metadata_value' => $uuid));

	if ($entities) {
		return $entities[0];
	}

	return false;
}

/**
 * Tag a previously created guid with the uuid it was imported on.
 *
 * @param int $guid
 * @param string $uuid
 */
function add_uuid_to_guid($guid, $uuid) {
	$guid = (int)$guid;
	$uuid = sanitise_string($uuid);

	return create_metadata($guid, "import_uuid", $uuid);
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
 */
function __process_element(ODD $odd) {
	global $IMPORTED_DATA, $IMPORTED_OBJECT_COUNTER;

	// See if anyone handles this element, return true if it is.
	if ($odd) {
		$handled = trigger_plugin_hook("import", "all", array("element" => $odd), $to_be_serialised);
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

function exportAsArray($guid) {
	$guid = (int)$guid;

	// Initialise the array
	$to_be_serialised = array();

	// Trigger a hook to
	$to_be_serialised = trigger_plugin_hook("export", "all", array("guid" => $guid), $to_be_serialised);

	// Sanity check
	if ((!is_array($to_be_serialised)) || (count($to_be_serialised)==0)) {
		throw new ExportException(sprintf(elgg_echo('ExportException:NoSuchEntity'), $guid));
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
 * @see ElggEntity for an example of its usage.
 * @param int $guid The GUID.
 * @param ODDWrapperFactory $wrapper Optional wrapper permitting the export process to embed ODD in other document formats.
 * @return xml
 */
function export($guid) {
	$odd = new ODDDocument(exportAsArray($guid));

	return ODD_Export($odd);
}

/**
 * Import an XML serialisation of an object.
 * This will make a best attempt at importing a given xml doc.
 *
 * @param string $xml
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
		__process_element($element);
	}

	if ($IMPORTED_OBJECT_COUNTER!= count($IMPORTED_DATA)) {
		throw new ImportException(elgg_echo('ImportException:NotAllImported'));
	}

	return true;
}


/**
 * Register the OpenDD import action
 */
function export_init() {
	global $CONFIG;

	register_action("import/opendd", false);
}

// Register a startup event
register_elgg_event_handler('init', 'system', 'export_init', 100);
