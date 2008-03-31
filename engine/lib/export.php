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
	 * Define an interface for all exportable objects.
	 * @author Marcus Povey
	 */
	interface Exportable
	{
		/**
		 * This must take the contents of the object and return it as a stdClass.
		 */
	    public function export();
	}

	/**
	 * Define an interface for all importable objects.
	 * @author Marcus Povey
	 */
	interface Importable
	{
		/**
		 * Accepts an array of data to import, this data is parsed from the XML produced by export.
		 * The function should return the constructed object data, or NULL.
		 *
		 * @param array $data
		 * @param int $version Support different internal serialisation formats, should be "1"
		 * @throws ImportException if there was a critical error importing data.
		 */
		public function import(array $data, $version = 1);
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
	 * @return xml 
	 */
	function export($guid)
	{
		global $CONFIG;
		
		$guid = (int)$guid;  
		
		// Initialise the array
		$to_be_serialised = array();
		
		// Trigger a hook to 
		$to_be_serialised = trigger_plugin_hook("export", "all", array("guid" => $guid), $to_be_serialised);
		
		// Sanity check
		if ((!is_array($to_be_serialised)) || (count($to_be_serialised)==0)) throw new ExportException("No such entity GUID:$guid");
		
		// Now serialise the result to XML
		$wrapper = new stdClass;
	
		// Construct header
		$wrapper->header = new stdClass;
		$wrapper->header->date = date("r");
		$wrapper->header->timestamp = time();
		$wrapper->header->domain = $CONFIG->wwwroot;
		$wrapper->header->guid = $guid;
		$wrapper->header->uuid = guid_to_uuid($guid);
		$wrapper->header->exported_by = guid_to_uuid($_SESSION['id']);
		
		// Construct data
		$wrapper->data = $to_be_serialised;
	
		return serialise_object_to_xml($wrapper, "elggexport");
	}
	

	/**
	 * XML 2 Array function.
	 * Taken from http://www.bytemycode.com/snippets/snippet/445/
	 * @license UNKNOWN - Please contact if you are the original author of this code.
	 * @author UNKNOWN
	 */
	function __xml2array($xml) 
	{
        $xmlary = array();
               
        $reels = '/<(\w+)\s*([^\/>]*)\s*(?:\/>|>(.*)<\/\s*\\1\s*>)/s';
        $reattrs = '/(\w+)=(?:"|\')([^"\']*)(:?"|\')/';

        preg_match_all($reels, $xml, $elements);

        foreach ($elements[1] as $ie => $xx) {
	        $xmlary[$ie]["name"] = $elements[1][$ie];
	       
	        if ($attributes = trim($elements[2][$ie])) {
	                preg_match_all($reattrs, $attributes, $att);
	                foreach ($att[1] as $ia => $xx)
	                        $xmlary[$ie]["attributes"][$att[1][$ia]] = $att[2][$ia];
	        }
	
	        $cdend = strpos($elements[3][$ie], "<");
	        if ($cdend > 0) {
	                $xmlary[$ie]["text"] = substr($elements[3][$ie], 0, $cdend - 1);
	        }
	
	        if (preg_match($reels, $elements[3][$ie]))
	                $xmlary[$ie]["elements"] = __xml2array($elements[3][$ie]);
	        else if ($elements[3][$ie]) {
	                $xmlary[$ie]["text"] = $elements[3][$ie];
	        }
        }

        return $xmlary;
	}

	$IMPORTED_DATA = array();
	$IMPORTED_OBJECT_COUNTER = 0;
	
	/**
	 * This function processes an element, passing elements to the plugin stack to see if someone will
	 * process it.
	 * If nobody processes the top level element, the sub level elements are processed.
	 */
	function __process_element(array $dom)
	{
		global $IMPORTED_DATA, $IMPORTED_OBJECT_COUNTER;
		
		foreach ($dom as $element)
		{
			// See if anyone handles this element, return true if it is.
			$handled = trigger_plugin_hook("import", "all", array("name" => $element['name'], "element" => $element), $to_be_serialised);
		
			// If not, then see if any of its sub elements are handled
			if (!$handled) 
			{
				if (isset($element['elements'])) 
					__process_element($element['elements']);
			}
			else
			{
				$IMPORTED_OBJECT_COUNTER ++; // Increment validation counter
				$IMPORTED_DATA[] = $handled; // Return the constructed object
			}
		}
	}


	
	/**
	 * Import an XML serialisation of an object.
	 * This will make a best attempt at importing a given xml doc.
	 *
	 * @param string $xml
	 * @return array An array of imported objects (these have already been saved).
	 * @throws Exception if there was a problem importing the data.
	 */
	function import($xml)
	{
		global $IMPORTED_DATA, $IMPORTED_OBJECT_COUNTER;
		
		$IMPORTED_DATA = array();
		$IMPORTED_OBJECT_COUNTER = 0;
		
		$dom = __xml2array($xml);
		
		__process_element($dom);
		
		if ($IMPORTED_OBJECT_COUNTER!= count($IMPORTED_DATA))
			throw new ImportException("Not all elements were imported.");
		
		return $IMPORTED_DATA;
	}

	/**
	 * Generate a UUID from a given GUID.
	 * 
	 * @param int $guid The GUID of an object.
	 */
	function guid_to_uuid($guid)
	{
		global $CONFIG;
		
		return "UUID:".md5($CONFIG->wwwroot)  . ":$guid";
	}
	
	/**
	 * Test to see if a given uuid is for this domain, returning true if so.
	 * @param $uuid
	 * @return bool
	 */
	function is_uuid_this_domain($uuid)
	{
		global $CONFIG;
		
		$domain = md5($CONFIG->wwwroot);
		$tmp = explode(":",$uuid);
		
		if (strcmp($tmp[1], $domain) == 0)
			return true;
			
		return false;
	}
	
	/**
	 * This function serialises an object recursively into an XML representation.
	 * The function attempts to call $data->export() which expects a stdClass in return, otherwise it will attempt to
	 * get the object variables using get_object_vars (which will only return public variables!)
	 * @param $data object The object to serialise.
	 * @param $n int Level, only used for recursion.
	 * @return string The serialised XML output.
	 */
	function serialise_object_to_xml($data, $name = "", $n = 0)
	{
		$classname = ($name=="" ? get_class($data) : $name);
		
		$vars = method_exists($data, "export") ? get_object_vars($data->export()) : get_object_vars($data); 
		
		$output = "";
		
		if (($n==0) || ( is_object($data) && !($data instanceof stdClass))) $output = "<$classname>";

		foreach ($vars as $key => $value)
		{
			$output .= "<$key type=\"".gettype($value)."\">";
			
			if (is_object($value))
				$output .= serialise_object_to_xml($value, $key, $n+1);
			else if (is_array($value))
				$output .= serialise_array_to_xml($value, $n+1);
			else
				$output .= htmlentities($value);
			
			$output .= "</$key>\n";
		}
		
		if (($n==0) || ( is_object($data) && !($data instanceof stdClass))) $output .= "</$classname>\n";
		
		return $output;
	}

	/**
	 * Serialise an array.
	 *
	 * @param array $data
	 * @param int $n Used for recursion
	 * @return string
	 */
	function serialise_array_to_xml(array $data, $n = 0)
	{
		$output = "";
		
		if ($n==0) $output = "<array>\n";
		
		foreach ($data as $key => $value)
		{
			$item = "array_item";
			
			if (is_numeric($key))
				$output .= "<$item name=\"$key\" type=\"".gettype($value)."\">";
			else
			{
				$item = $key;
				$output .= "<$item type=\"".gettype($value)."\">";
			}
			
			if (is_object($value))
				$output .= serialise_object_to_xml($value, "", $n+1);
			else if (is_array($value))
				$output .= serialise_array_to_xml($value, $n+1);
			else
				$output .= htmlentities($value);
			
			$output .= "</$item>\n";
		}
		
		if ($n==0) $output = "</array>\n";
		
		return $output;
	}
	
	class ExportException extends Exception {}
	class ImportException extends Exception {}
	
?>