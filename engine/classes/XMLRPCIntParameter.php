<?php
/**
 * @class XMLRPCIntParameter An Integer.
 * @author Curverider Ltd
 */
class XMLRPCIntParameter extends XMLRPCParameter
{
	function __construct($value)
	{
		parent::__construct();
		
		$this->value = (int)$value; 
	}
	
	function __toString() 
	{
		return "<value><i4>{$this->value}</i4></value>";
	}
}
