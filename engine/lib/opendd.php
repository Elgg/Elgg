<?php
/**
 * OpenDD PHP Library.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @version 0.4
 * @link http://elgg.org/
 */

include_once("xml.php");

/**
 * @class ODDDocument ODD Document container.
 * This class is used during import and export to construct.
 * @author Curverider Ltd
 */
class ODDDocument implements Iterator {
	/**
	 * ODD Version
	 *
	 * @var string
	 */
	private $ODDSupportedVersion = "1.0";

	/**
	 * Elements of the document.
	 */
	private $elements;

	/**
	 * Optional wrapper factory.
	 */
	private $wrapperfactory;

	public function __construct(array $elements = NULL) {
		if ($elements) {
			if (is_array($elements)) {
				$this->elements = $elements;
			} else {
				$this->addElement($elements);
			}
		} else {
			$this->elements = array();
		}
	}

	/**
	 * Return the version of ODD being used.
	 *
	 * @return string
	 */
	public function getVersion() {
		return $this->ODDSupportedVersion;
	}

	public function getNumElements() {
		return count($this->elements);
	}

	public function addElement(ODD $element) {
		if (!is_array($this->elements)) {
			$this->elements = array();
			$this->elements[] = $element;
		}
	}

	public function addElements(array $elements) {
		foreach ($elements as $element) {
			$this->addElement($element);
		}
	}

	public function getElements() {
		return $this->elements;
	}

	/**
	 * Set an optional wrapper factory to optionally embed the ODD document in another format.
	 */
	public function setWrapperFactory(ODDWrapperFactory $factory) {
		$this->wrapperfactory = $factory;
	}

	/**
	 * Magic function to generate valid ODD XML for this item.
	 */
	public function __toString() {
		$xml = "";

		if ($this->wrapperfactory) {
			// A wrapper has been provided
			$wrapper = $this->wrapperfactory->getElementWrapper($this); // Get the wrapper for this element

			$xml = $wrapper->wrap($this); // Wrap this element (and subelements)
		} else {
			// Output begin tag
			$generated = date("r");
			$xml .= "<odd version=\"{$this->ODDSupportedVersion}\" generated=\"$generated\">\n";

			// Get XML for elements
			foreach ($this->elements as $element) {
				$xml .= "$element";
			}

			// Output end tag
			$xml .= "</odd>\n";
		}

		return $xml;
	}

	// ITERATOR INTERFACE //////////////////////////////////////////////////////////////
	/*
	 * This lets an entity's attributes be displayed using foreach as a normal array.
	 * Example: http://www.sitepoint.com/print/php5-standard-library
	 */

	private $valid = FALSE;

	function rewind() {
		$this->valid = (FALSE !== reset($this->elements));
	}

	function current() {
		return current($this->elements);
	}

	function key() {
		return key($this->elements);
	}

	function next() {
		$this->valid = (FALSE !== next($this->elements));
	}

	function valid() {
		return $this->valid;
	}
}

/**
 * Open Data Definition (ODD) superclass.
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 */
abstract class ODD {
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
	public function __construct() {
		$this->body = "";
	}

	public function getAttributes() {
		return $this->attributes;
	}

	public function setAttribute($key, $value) {
		$this->attributes[$key] = $value;
	}

	public function getAttribute($key) {
		if (isset($this->attributes[$key])) {
			return $this->attributes[$key];
		}

		return NULL;
	}

	public function setBody($value) {
		$this->body = $value;
	}

	public function getBody() {
		return $this->body;
	}

	/**
	 * Set the published time.
	 *
	 * @param int $time Unix timestamp
	 */
	public function setPublished($time) {
		$this->attributes['published'] = date("r", $time);
	}

	/**
	 * Return the published time as a unix timestamp.
	 *
	 * @return int or false on failure.
	 */
	public function getPublishedAsTime() {
		return strtotime($this->attributes['published']);
	}

	/**
	 * For serialisation, implement to return a string name of the tag eg "header" or "metadata".
	 * @return string
	 */
	abstract protected function getTagName();

	/**
	 * Magic function to generate valid ODD XML for this item.
	 */
	public function __toString() {
		// Construct attributes
		$attr = "";
		foreach ($this->attributes as $k => $v) {
			$attr .= ($v!="") ? "$k=\"$v\" " : "";
		}

		$body = $this->getBody();
		$tag = $this->getTagName();

		$end = "/>";
		if ($body!="") {
			$end = "><![CDATA[$body]]></{$tag}>";
		}

		return "<{$tag} $attr" . $end . "\n";
	}
}

/**
 * ODD Entity class.
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 */
class ODDEntity extends ODD {
	function __construct($uuid, $class, $subclass = "") {
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
 * @author Curverider Ltd
 */
class ODDMetaData extends ODD {
	function __construct($uuid, $entity_uuid, $name, $value, $type = "", $owner_uuid = "") {
		parent::__construct();

		$this->setAttribute('uuid', $uuid);
		$this->setAttribute('entity_uuid', $entity_uuid);
		$this->setAttribute('name', $name);
		$this->setAttribute('type', $type);
		$this->setAttribute('owner_uuid', $owner_uuid);
		$this->setBody($value);
	}

	protected function getTagName() {
		return "metadata";
	}
}

/**
 * ODD Relationship class.
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 */
class ODDRelationship extends ODD {
	function __construct($uuid1, $type, $uuid2) {
		parent::__construct();

		$this->setAttribute('uuid1', $uuid1);
		$this->setAttribute('type', $type);
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
function ODD_factory(XmlElement $element) {
	$name = $element->name;
	$odd = false;

	switch ($name) {
		case 'entity' :
			$odd = new ODDEntity("","","");
			break;
		case 'metadata' :
			$odd = new ODDMetaData("","","","");
			break;
		case 'relationship' :
			$odd = new ODDRelationship("","","");
			break;
	}

	// Now populate values
	if ($odd) {
		// Attributes
		foreach ($element->attributes as $k => $v) {
			$odd->setAttribute($k,$v);
		}

		// Body
		$body = $element->content;
		$a = stripos($body, "<![CDATA");
		$b = strripos($body, "]]>");
		if (($body) && ($a!==false) && ($b!==false)) {
			$body = substr($body, $a+8, $b-($a+8));
		}

		$odd->setBody($body);
	}

	return $odd;
}

/**
 * Import an ODD document.
 *
 * @param string $xml The XML ODD.
 * @return ODDDocument
 */
function ODD_Import($xml) {
	// Parse XML to an array
	$elements = xml_to_object($xml);

	// Sanity check 1, was this actually XML?
	if ((!$elements) || (!$elements->children)) {
		return false;
	}

	// Create ODDDocument
	$document = new ODDDocument();

	// Itterate through array of elements and construct ODD document
	$cnt = 0;

	foreach ($elements->children as $child) {
		$odd = ODD_factory($child);

		if ($odd) {
			$document->addElement($odd);
			$cnt++;
		}
	}

	// Check that we actually found something
	if ($cnt == 0) {
		return false;
	}

	return $document;
}

/**
 * Export an ODD Document.
 *
 * @param ODDDocument $document The Document.
 * @param ODDWrapperFactory $wrapper Optional wrapper permitting the export process to embed ODD in other document formats.
 */
function ODD_Export(ODDDocument $document) {
	return "$document";
}