<?php
/**
 * A double precision signed floating point number.
 *
 * @package    Elgg.Core
 * @subpackage XMLRPC
 */
class XMLRPCDoubleParameter extends XMLRPCParameter {

	/**
	 * New XML Double
	 *
	 * @param int $value Value
	 */
	function __construct($value) {
		parent::__construct();

		$this->value = (float)$value;
	}

	/**
	 * Convert to string
	 *
	 * @return string
	 */
	function __toString() {
		return "<value><double>{$this->value}</double></value>";
	}
}
