<?php
/**
 * Parent class for manifest parsers.
 *
 * @package    Elgg.Core
 * @subpackage Plugins
 *
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
	 * @param XmlElement $xml    The Manifest XML to be parsed
	 * @param object     $caller The object calling this parser.
	 */
	public function __construct(XmlElement $xml, $caller) {
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
		if (array_key_exists($name, $this->validAttributes)) {
			if (isset($this->manifest[$name])) {
				return $this->manifest[$name];
			} else {
				return $this->validAttributes[$name];
			}
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