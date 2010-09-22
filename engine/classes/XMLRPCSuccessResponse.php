<?php
/**
 * @class XMLRPCSuccessResponse
 * @author Curverider Ltd
 */
class XMLRPCSuccessResponse extends XMLRPCResponse
{
	/**
	 * Output to XML.
	 */
	public function __toString()
	{
		$params = "";
		foreach ($this->parameters as $param)
			$params .= "<param>$param</param>\n";
		
		return "<methodResponse><params>$params</params></methodResponse>";
	}
}