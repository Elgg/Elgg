<?php

/**
 * @class XMLRPCStructParameter A structure containing other XMLRPCParameter objects.
 * @author Curverider Ltd
 */
class XMLRPCStructParameter extends XMLRPCParameter
{
	/**
	 * Construct a struct.
	 *
	 * @param array $parameters Optional associated array of parameters, if not provided then addField must be used.
	 */
	function __construct($parameters = NULL)
	{
		parent::__construct();
		
		if (is_array($parameters))
		{
			foreach ($parameters as $k => $v)
				$this->addField($k, $v);
		}
	}
	
	/**
	 * Add a field to the container.
	 *
	 * @param string $name The name of the field.
	 * @param XMLRPCParameter $value The value.
	 */
	public function addField($name, XMLRPCParameter $value)
	{
		if (!is_array($this->value))
			$this->value = array();
			
		$this->value[$name] = $value;
	}
	
	function __toString() 
	{
		$params = "";
		foreach ($this->value as $k => $v)
		{
			$params .= "<member><name>$k</name>$v</member>";
		}
		
		return "<value><struct>$params</struct></value>";
	}
}