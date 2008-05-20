<?php
	/**
	 * Elgg Data import export functionality.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Define an interface for all ODD exportable objects.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Marcus Povey
	 */
	interface Exportable
	{
		/**
		 * This must take the contents of the object and convert it to exportable class(es).
		 * @return object or array of objects.
		 */
	    public function export();
	    
	}

	/**
	 * Define an interface for all ODD importable objects.
	 * @author Marcus Povey
	 */
	interface Importable
	{
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
	 * Generate a UUID from a given GUID.
	 * 
	 * @param int $guid The GUID of an object.
	 */
	function guid_to_uuid($guid)
	{
		global $CONFIG;
		
		return $CONFIG->wwwroot  . "odd/$guid/";
	}
	
	/**
	 * Test to see if a given uuid is for this domain, returning true if so.
	 * @param $uuid
	 * @return bool
	 */
	function is_uuid_this_domain($uuid)
	{
		global $CONFIG;
		
		if (strpos($uuid, $CONFIG->wwwroot) === 0)
			return true;
			
		return false;
	}
	
	/**
	 * This function attempts to retrieve a previously imported entity via its UUID.
	 * 
	 * @param $uuid 
	 */
	function get_entity_from_uuid($uuid)
	{
		$uuid = sanitise_string($uuid);
		
		$entities = get_entities_from_metadata("import_uuid", $uuid);
		
		if ($entities)
			return $entities[0];
		
		return false;
	}
	
	/**
	 * Tag a previously created guid with the uuid it was imported on.
	 *
	 * @param int $guid
	 * @param string $uuid
	 */
	function add_uuid_to_guid($guid, $uuid)
	{
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
	function __process_element(ODD $odd)
	{
		global $IMPORTED_DATA, $IMPORTED_OBJECT_COUNTER;
		
		// See if anyone handles this element, return true if it is.
		if ($odd)
			$handled = trigger_plugin_hook("import", "all", array("element" => $odd), $to_be_serialised);

		// If not, then see if any of its sub elements are handled
		if ($handled) 
		{
			$IMPORTED_OBJECT_COUNTER ++; // Increment validation counter
			$IMPORTED_DATA[] = $handled; // Return the constructed object

			return true;
		}
		
		return false;
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
	function export($guid, ODDWrapperFactory $wrapper = null)
	{
		$guid = (int)$guid;  
		
		// Initialise the array
		$to_be_serialised = array();
		
		// Trigger a hook to 
		$to_be_serialised = trigger_plugin_hook("export", "all", array("guid" => $guid), $to_be_serialised);
		
		// Sanity check
		if ((!is_array($to_be_serialised)) || (count($to_be_serialised)==0)) throw new ExportException("No such entity GUID:$guid");
		
		$odd = new ODDDocument($to_be_serialised);
		
		return ODD_Export($odd, $wrapper);
	}
	
	/**
	 * Import an XML serialisation of an object.
	 * This will make a best attempt at importing a given xml doc.
	 *
	 * @param string $xml
	 * @return bool
	 * @throws Exception if there was a problem importing the data.
	 */
	function import($xml)
	{
		global $IMPORTED_DATA, $IMPORTED_OBJECT_COUNTER;
	
		$IMPORTED_DATA = array();
		$IMPORTED_OBJECT_COUNTER = 0;
		
		$document = ODD_Import($xml);
		
		foreach ($document as $element)
			__process_element($element);
		
		if ($IMPORTED_OBJECT_COUNTER!= count($IMPORTED_DATA))
			throw new ImportException("Not all elements were imported.");
		
		return true;
	}
	
?>