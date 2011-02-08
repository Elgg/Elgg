<?php
/**
 * A base 64 encoded blob of binary.
 *
 * @package    Elgg.Core
 * @subpackage XMLRPC
 */
class XMLRPCBase64Parameter extends XMLRPCParameter {
	/**
	 * Construct a base64 encoded block
	 *
	 * @param string $blob Unencoded binary blob
	 */
	function __construct($blob) {
		parent::__construct();

		$this->value = base64_encode($blob);
	}

	/**
	 * Convert to string
	 *
	 * @return string
	 */
	function __toString() {
		return "<value><base64>{$value}</base64></value>";
	}
}
