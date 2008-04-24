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
		}
		
		public function setAttribute($key, $value) { $this->attributes[$key] = $value; }
		public function getAttribute($key) 
		{ 
			if (isset($this->attributes[$key]))
				return $this->attributes[$key];
				
			return NULL;
		}
		public function setBody($value) { $this->body = $value; }
		public function getBody() { return $this->body; }
		
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
		function __construct($uuid, $entity_uuid, $name, $value, $type = "", $owner_uuid = "")
		{
			parent::__construct();
			
			$this->setAttribute('uuid', $uuid);
			$this->setAttribute('entity_uuid', $entity_uuid);
			$this->setAttribute('name', $name);
			$this->setAttribute('type', $type);	
			$this->setAttribute('owner_uuid', $owner_uuid);
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
			
			$this->setAttribute('uuid1', $uuid1);
			$this->setAttribute('verb', $verb);
			$this->setAttribute('uuid2', $uuid2);
		}
		
		protected function getTagName() { return "relationship"; }
	}
	
	/**
	 * Attempt to construct an ODD object out of a XmlElement or sub-elements.
	 * 
	 * @param XmlElement $element The element(s)
	 * @return mixed An ODD object if the element can be handled, or false.
	 */
	function ODD_factory(XmlElement $element)
	{
		$name = $element->name; 
		$odd = false;
		
		switch ($name)
		{
			case 'entity' : $odd = new ODDEntity("","",""); break;
			case 'metadata' : $odd = new ODDMetaData("","","",""); break;
			case 'relationship' : $odd = new ODDRelationship("","",""); break;
		}
		
		// Now populate values
		if ($odd)
		{
			// Attributes
			foreach ($element->attributes as $k => $v)
				$odd->setAttribute($k,$v);
				
			// Body
			$odd->setBody($element->content);
		}
		
		return $odd;
	}
	
	/** Relationship verb mapping */
	$ODD_RELATIONSHIP_VERBS = array();
	
	/**
	 * This function provides a mapping between entity relationships and ODD relationship verbs.
	 * @param string $relationship The relationship as stored in the database, eg "friend" or "member of"
	 * @param string $verb The verb, eg "friends" or "joins"
	 */
	function register_odd_relationship_mapping($relationship, $verb)
	{
		global $ODD_RELATIONSHIP_VERBS;
		
		$ODD_RELATIONSHIP_VERBS[$relationship] = $verb;
	}
	
	/**
	 * Return a mapping for relationship to a pre-registered ODD verb, or false.
	 * @param string $relationship The relationship
	 */
	function get_verb_from_relationship($relationship)
	{
		global $ODD_RELATIONSHIP_VERBS;
		
		if (isset($ODD_RELATIONSHIP_VERBS[$relationship]))
			return $ODD_RELATIONSHIP_VERBS[$relationship];
		
		return false;
	}
	
	/**
	 * Return the relationship registered with a given verb, or false.
	 * @param string $verb The verb.
	 */
	function get_relationship_from_verb($verb)
	{
		global $ODD_RELATIONSHIP_VERBS;
		
		foreach ($ODD_RELATIONSHIP_VERBS as $k => $v)
			if ($v == $verb) return $k;
			
		return false;
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
	
	
	$IMPORTED_DATA = array();
	$IMPORTED_OBJECT_COUNTER = 0;
	
	/**
	 * This function processes an element, passing elements to the plugin stack to see if someone will
	 * process it.
	 * 
	 * If nobody processes the top level element, the sub level elements are processed.
	 * 
	 * @param array $element The dom tree.
	 */
	function __process_element($element)
	{
		global $IMPORTED_DATA, $IMPORTED_OBJECT_COUNTER;
				
		// See if we can convert the element into an ODD element
		$odd = ODD_factory($element);
		
		// See if anyone handles this element, return true if it is.
		if ($odd)
			$handled = trigger_plugin_hook("import", "all", array("element" => $odd), $to_be_serialised);

		// If not, then see if any of its sub elements are handled
		if (!$handled) 
		{
			// Issue a warning
			trigger_error("'<{$element->name}>' had no registered handler.", E_USER_WARNING);
			
			if (isset($element->children)) 
				foreach ($element->children as $c)
					__process_element($c);
		}
		else
		{
			$IMPORTED_OBJECT_COUNTER ++; // Increment validation counter
			$IMPORTED_DATA[] = $handled; // Return the constructed object

			return true;
		}
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
		
		__process_element(xml_2_object($xml));
		
		if ($IMPORTED_OBJECT_COUNTER!= count($IMPORTED_DATA))
			throw new ImportException("Not all elements were imported.");
		
		return true;
	}
	
?>