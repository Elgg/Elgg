<?php
/**
 * @class XMLRPCDoubleParameter A double precision signed floating point number.
 * @author Curverider Ltd
 */
class XMLRPCDoubleParameter extends XMLRPCParameter
{
	function __construct($value)
	{
		parent::__construct();
		
		$this->value = (float)$value; 
	}
	
	function __toString() 
	{
		return "<value><double>{$this->value}</double></value>";
	}
}
