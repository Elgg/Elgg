<?php

/**
 * @class XMLRPCResponse XML-RPC Response. 
 * @author Curverider Ltd
 */
abstract class XMLRPCResponse
{
	/** An array of parameters */
	protected $parameters = array();
	
	/**
	 * Add a parameter here.
	 *
	 * @param XMLRPCParameter $param The parameter.
	 */
	public function addParameter(XMLRPCParameter $param)
	{
		if (!is_array($this->parameters))
			$this->parameters = array();
			
		$this->parameters[] = $param;
	}

	public function addInt($value) { $this->addParameter(new XMLRPCIntParameter($value)); }
	public function addString($value) { $this->addParameter(new XMLRPCStringParameter($value)); }
	public function addDouble($value) { $this->addParameter(new XMLRPCDoubleParameter($value)); }
	public function addBoolean($value) { $this->addParameter(new XMLRPCBoolParameter($value)); }
}