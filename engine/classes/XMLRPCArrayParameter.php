<?php

/**
 * @class XMLRPCArrayParameter An array containing other XMLRPCParameter objects.
 */
class XMLRPCArrayParameter extends XMLRPCParameter
{
	/**
	 * Construct an array.
	 *
	 * @param array $parameters Optional array of parameters, if not provided then addField must be used.
	 */
	function __construct($parameters = NULL)
	{
		parent::__construct();
		
		if (is_array($parameters))
		{
			foreach ($parameters as $v)
				$this->addField($v);
		}
	}
	
	/**
	 * Add a field to the container.
	 *
	 * @param XMLRPCParameter $value The value.
	 */
	public function addField(XMLRPCParameter $value)
	{
		if (!is_array($this->value))
			$this->value = array();
			
		$this->value[] = $value;
	}
	
	function __toString() 
	{
		$params = "";
		foreach ($this->value as $value)
		{
			$params .= "$value";
		}
		
		return "<array><data>$params</data></array>";
	}
}