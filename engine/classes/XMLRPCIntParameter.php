<?php
/**
 * An Integer.
 *
 * @package    Elgg.Core
 * @subpackage XMLRPC
 */
class XMLRPCIntParameter extends XMLRPCParameter {

	/**
	 * A new XML int
	 *
	 * @param int $value Value
	 */
	function __construct($value) {
		parent::__construct();

		$this->value = (int)$value;
	}

	/**
	 * Convert to string
	 *
	 * @return string
	 */
	function __toString() {
		return "<value><i4>{$this->value}</i4></value>";
	}
}
