<?php
/**
 * @class XMLRPCStringParameter A string.
 */
class XMLRPCStringParameter extends XMLRPCParameter
{
	function __construct($value)
	{
		parent::__construct();
		
		$this->value = $value; 
	}
	
	function __toString() 
	{
		$value = htmlentities($this->value);
		return "<value><string>{$value}</string></value>";
	}
}
