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
	 * Define an interface for all importable objects.
	 * @author Marcus Povey
	 */
	interface Importable
	{
		/**
		 * Accepts an array of data to import, this data is parsed from the XML produced by export.
		 * The function should return the constructed object data, or NULL.
		 *
		 * @param ODD $data
		 * @return mixed The newly imported object.
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
	 * Open Data Definition (ODD) superclass.
	 * @package Elgg
	 * @subpackage Core
	 * @author Marcus Povey
	 */
	abstract class ODD
	{
		/**
		 * ODD Version
		 *
		 * @var string
		 */
		private $ODDSupportedVersion = "1.0"; 
		
		/**
		 * Attributes.
		 */
		private $attributes = array();
		
		/**
		 * Optional body.
		 */
		private $body;
		
		/**
		 * Construct an ODD document with initial values.
		 */
		public function __construct()
		{
			$this->body = "";
			$this->setAttribute('generated', date("r"));
		}
		
		protected function setAttribute($key, $value) { $this->attributes[$key] = $value; }
		protected function getAttribute($key) 
		{ 
			if (isset($this->attributes[$key]))
				return $this->attributes[$key];
				
			return NULL;
		}
		protected function setBody($value) { $this->body = $value; }
		protected function getBody() { return $this->body; }
		
		/**
		 * Return the version of ODD being used.
		 *
		 * @return real
		 */
		public function getVersion() { return $this->ODDSupportedVersion; }
		
		/**
		 * For serialisation, implement to return a string name of the tag eg "header" or "metadata".
		 * @return string
		 */
		abstract protected function getTagName();

		/**
		 * Magic function to generate valid ODD XML for this item.
		 */
		public function __toString()
		{
			// Construct attributes
			$attr = "";
			foreach ($this->attributes as $k => $v)
				$attr .= ($v!="") ? "$k=\"$v\" " : "";
			
			$body = $this->getBody();
			$tag = $this->getTagName();
			
			$end = "/>";
			if ($body!="")
				$end = ">$body</$tag>";
			
			return "<$tag $attr" . $end . "\n";  
		}
	}
	
	/**
	 * ODD Header class.
	 * @package Elgg
	 * @subpackage Core
	 * @author Marcus Povey
	 */
	class ODDHeader extends ODD
	{
		
		function __construct($extension = "SN1.0")
		{
			parent::__construct();
			
			$this->setAttribute('version', $this->getVersion());
			$this->setAttribute('extension', $extension);
		}
		
		protected function getTagName() { return "header"; }
	}
	
	/**
	 * ODD Entity class.
	 * @package Elgg
	 * @subpackage Core
	 * @author Marcus Povey
	 */
	class ODDEntity extends ODD
	{
		function __construct($uuid, $class, $subclass = "")
		{
			parent::__construct();
			
			$this->setAttribute('uuid', $uuid);
			$this->setAttribute('class', $class);
			$this->setAttribute('subclass', $subclass);	
		}
		
		protected function getTagName() { return "entity"; }
	}
	
	/**
	 * ODD Metadata class.
	 * @package Elgg
	 * @subpackage Core
	 * @author Marcus Povey
	 */
	class ODDMetaData extends ODD
	{
		function __construct($uuid, $entity_uuid, $name, $value, $type = "")
		{
			parent::__construct();
			
			$this->setAttribute('uuid', $uuid);
			$this->setAttribute('entity_uuid', $entity_uuid);
			$this->setAttribute('name', $name);
			$this->setAttribute('type', $type);	
			$this->setBody($value);
		}
		
		protected function getTagName() { return "metadata"; }
	}
	
	/**
	 * ODD Relationship class.
	 * @package Elgg
	 * @subpackage Core
	 * @author Marcus Povey
	 */
	class ODDRelationship extends ODD
	{
		function __construct($uuid1, $verb, $uuid2)
		{
			parent::__construct();
			
			$this->setAttribute('uuid1', $uuid);
			$this->setAttribute('verb', $uuid);
			$this->setAttribute('uuid2', $uuid);
		}
		
		protected function getTagName() { return "relationship"; }
	}
	
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
		$guid = (int)$guid;  
		
		// Initialise the array
		$to_be_serialised = array();
		
		// Set header
		$to_be_serialised[] = new ODDHeader();
		
		// Trigger a hook to 
		$to_be_serialised = trigger_plugin_hook("export", "all", array("guid" => $guid), $to_be_serialised);
		
		// Sanity check
		if ((!is_array($to_be_serialised)) || (count($to_be_serialised)==0)) throw new ExportException("No such entity GUID:$guid");
		
		$output = "<odd>\n";
		
		foreach ($to_be_serialised as $odd)
			$output .= "$odd";
		
		$output .= "</odd>\n";
		
		return $output;
	}
	
	
	

	

	$IMPORTED_DATA = array();
	$IMPORTED_OBJECT_COUNTER = 0;
	
	/**
	 * This function processes an element, passing elements to the plugin stack to see if someone will
	 * process it.
	 * If nobody processes the top level element, the sub level elements are processed.
	 */
/*	function __process_element(array $dom)
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
/*	function import($xml)
	{
		global $IMPORTED_DATA, $IMPORTED_OBJECT_COUNTER;
		
		$IMPORTED_DATA = array();
		$IMPORTED_OBJECT_COUNTER = 0;
		$IMPORTED_GUID_MAP = array();
		
		$dom = __xml2array($xml);
		
		__process_element($dom);
		
		if ($IMPORTED_OBJECT_COUNTER!= count($IMPORTED_DATA))
			throw new ImportException("Not all elements were imported.");
		
		return $IMPORTED_DATA;
	}

	
	*/
?>