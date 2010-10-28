<?php
/**
 * A string.
 *
 * @package    Elgg.Core
 * @subpackage XMLRPC
 */
class XMLRPCStringParameter extends XMLRPCParameter {

	/**
	 * A new XML string
	 *
	 * @param string $value Value
	 */
	function __construct($value) {
		parent::__construct();

		$this->value = $value;
	}

	/**
	 * Convert to XML string
	 *
	 * @return string
	 */
	function __toString() {
		$value = htmlentities($this->value);
		return "<value><string>{$value}</string></value>";
	}
}
