<?php
/**
 * @class XMLRPCBoolParameter A boolean.
 * @author Curverider Ltd
 */
class XMLRPCBoolParameter extends XMLRPCParameter
{
	function __construct($value)
	{
		parent::__construct();
		
		$this->value = (bool)$value; 
	}
	
	function __toString() 
	{
		$code = ($this->value) ? "1" : "0";
		return "<value><boolean>{$code}</boolean></value>";
	}
}