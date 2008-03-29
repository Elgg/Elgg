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
	 */
	interface Exportable
	{
		/**
		 * This must take the contents of the object and return it as a stdClass.
		 */
	    public function export();
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
		

		/*
		  	XML will look something like this:


			<elgg>
				<elgguser uuid="skdfjslklkjsldkfsdfjs:556">
					<guid>556</guid>
					<name>Marcus Povey</name>

					...
				
				</elgguser>
				<annotation>
					<name>Foo</name>
					<value>baaaa</value>
				</annotation>
				<annotation>
					<name>Monkey</name>
					<value>bibble</value>
				</annotation>

				...

				<metadata>
					<name>Foo</name>
					<value>baaaa</value>
				</metadata>

				...

				<my_plugin>

					...

				</my_plugin>

			</elgg> 
		 
		 */
		
	}
	
	/**
	 * Import an XML serialisation of an object.
	 * This will make a best attempt at importing a given xml doc.
	 *
	 * @param string $xml
	 * @return int The new GUID of the object.
	 */
	function import($xml)
	{
		// import via object ? 

		// import via tag : so you pass a tag "<foo>" and all its contents out and something answers by handling it.
		// THis is recursive but bredth first.

		
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