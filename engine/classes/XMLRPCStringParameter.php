<?php
/**
 * @class XMLRPCStringParameter A string.
 * @author Curverider Ltd
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
