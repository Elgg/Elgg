<?php
/**
 * A parser for XML that uses SimpleXMLElement
 *
 * @package    Elgg.Core
 * @subpackage XML
 */
class ElggXMLElement {
	/**
	 * @var SimpleXMLElement
	 */
	private $_element;

	/**
	 * Creates an ElggXMLParser from a string or existing SimpleXMLElement
	 * 
	 * @param string|SimpleXMLElement $xml The XML to parse
	 */
	public function __construct($xml) {
		if ($xml instanceof SimpleXMLElement) {
			$this->_element = $xml;
		} else {
			$this->_element = new SimpleXMLElement($xml);
		}
	}

	/**
	 * @return string The name of the element
	 */
	public function getName() {
		return $this->_element->getName();
	}

	/**
	 * @return string[] The attributes
	 */
	public function getAttributes() {
		//include namespace declarations as attributes
		$xmlnsRaw = $this->_element->getNamespaces();
		$xmlns = array();
		foreach ($xmlnsRaw as $key => $val) {
			$label = 'xmlns' . ($key ? ":$key" : $key);
			$xmlns[$label] = $val;
		}
		//get attributes and merge with namespaces
		$attrRaw = $this->_element->attributes();
		$attr = array();
		foreach ($attrRaw as $key => $val) {
			$attr[$key] = $val;
		}
		$attr = array_merge((array) $xmlns, (array) $attr);
		$result = array();
		foreach ($attr as $key => $val) {
			$result[$key] = (string) $val;
		}
		return $result;
	}

	/**
	 * @return string CData
	 */
	public function getContent() {
		return (string) $this->_element;
	}

	/**
	 * @return ElggXMLElement[] Child elements
	 */
	public function getChildren() {
		$children = $this->_element->children();
		$result = array();
		foreach ($children as $val) {
			$result[] = new ElggXMLElement($val);
		}

		return $result;
	}

	function __get($name) {
		switch ($name) {
			case 'name':
				return $this->getName();
				break;
			case 'attributes':
				return $this->getAttributes();
				break;
			case 'content':
				return $this->getContent();
				break;
			case 'children':
				return $this->getChildren();
				break;
		}
		return null;
	}

	function __isset($name) {
		switch ($name) {
			case 'name':
				return $this->getName() !== null;
				break;
			case 'attributes':
				return $this->getAttributes() !== null;
				break;
			case 'content':
				return $this->getContent() !== null;
				break;
			case 'children':
				return $this->getChildren() !== null;
				break;
		}
		return false;
	}

}