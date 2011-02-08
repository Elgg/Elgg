<?php

/**
 * XMLRPC Error Response
 *
 * @package    Elgg.Core
 * @subpackage XMLRPC
 */
class XMLRPCErrorResponse extends XMLRPCResponse {
	/**
	 * Set the error response and error code.
	 *
	 * @param string $message The message
	 * @param int    $code    Error code (default = system error as defined by
	 *                        http://xmlrpc-epi.sourceforge.net/specs/rfc.fault_codes.php)
	 */
	function __construct($message, $code = -32400) {
		$this->addParameter(
			new XMLRPCStructParameter(
				array (
					'faultCode' => new XMLRPCIntParameter($code),
					'faultString' => new XMLRPCStringParameter($message)
				)
			)
		);
	}

	/**
	 * Output to XML.
	 *
	 * @return string
	 */
	public function __toString() {
		return "<methodResponse><fault><value>{$this->parameters[0]}</value></fault></methodResponse>";
	}
}
