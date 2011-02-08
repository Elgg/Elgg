<?php
/**
 * Success Response
 *
 * @package    Elgg.Core
 * @subpackage XMLRPC
 */
class XMLRPCSuccessResponse extends XMLRPCResponse {
	/**
	 * Output to XML.
	 *
	 * @return string
	 */
	public function __toString() {
		$params = "";
		foreach ($this->parameters as $param) {
			$params .= "<param>$param</param>\n";
		}

		return "<methodResponse><params>$params</params></methodResponse>";
	}
}
