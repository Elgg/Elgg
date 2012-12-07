<?php
/**
 * Parent class for manifest parsers.
 *
 * Converts manifest.xml files or strings to an array.
 *
 * This should be extended by a class that does the actual work
 * to convert based on the manifest.xml version.
 *
 * This class only parses XML to an XmlEntity object and
 * an array.  The array should be used primarily to extract
 * information since it is quicker to parse once and store
 * values from the XmlElement object than to parse the object
 * each time.
 *
 * The array should be an exact representation of the manifest.xml
 * file or string.  Any normalization needs to be done in the
 * calling class / function.
 *
 * @package    Elgg.Core
 * @subpackage Plugins
 * @since      1.8
 */
abstract class ElggPluginManifestParser {
	/**
	 * The XmlElement object
	 *
	 * @var XmlElement
	 */
	protected $manifestObject;

	/**
	 * The manifest array
	 *
	 * @var array
	 */
	protected $manifest;

	/**
	 * All valid manifest attributes with default values.
	 *
	 * @var array
	 */
	protected $validAttributes;

	/**
	 * The object we're doing parsing for.
	 *
	 * @var object
	 */
	protected $caller;

	/**
	 * Loads the manifest XML to be parsed.
	 *
	 * @param ElggXmlElement $xml    The Manifest XML object to be parsed
	 * @param object         $caller The object calling this parser.
	 */
	public function __construct(ElggXMLElement $xml, $caller) {
		$this->manifestObject = $xml;
		$this->caller = $caller;
	}

	/**
	 * Returns the manifest XML object
	 *
	 * @return XmlElement
	 */
	public function getManifestObject() {
		return $this->manifestObject;
	}

	/**
	 * Return the parsed manifest array
	 *
	 * @return array
	 */
	public function getManifest() {
		return $this->manifest;
	}

	/**
	 * Return an attribute in the manifest.
	 *
	 * @param string $name Attribute name
	 * @return mixed
	 */
	public function getAttribute($name) {
		if (in_array($name, $this->validAttributes) && isset($this->manifest[$name])) {
			return $this->manifest[$name];
		}

		return false;
	}

	/**
	 * Parse the XML object into an array
	 *
	 * @return bool
	 */
	abstract public function parse();
}
