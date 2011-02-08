<?php

/**
 * XML-RPC Response.
 *
 * @package    Elgg.Core
 * @subpackage XMLRPC
 */
abstract class XMLRPCResponse {
	/** An array of parameters */
	protected $parameters = array();

	/**
	 * Add a parameter here.
	 *
	 * @param XMLRPCParameter $param The parameter.
	 *
	 * @return void
	 */
	public function addParameter(XMLRPCParameter $param) {
		if (!is_array($this->parameters)) {
			$this->parameters = array();
		}

		$this->parameters[] = $param;
	}

	/**
	 * Add an integer
	 *
	 * @param int $value Value
	 *
	 * @return void
	 */
	public function addInt($value) {
		$this->addParameter(new XMLRPCIntParameter($value));
	}

	/**
	 * Add a string
	 *
	 * @param string $value Value
	 *
	 * @return void
	 */
	public function addString($value) {
		$this->addParameter(new XMLRPCStringParameter($value));
	}

	/**
	 * Add a double
	 *
	 * @param int $value Value
	 *
	 * @return void
	 */
	public function addDouble($value) {
		$this->addParameter(new XMLRPCDoubleParameter($value));
	}

	/**
	 * Add a boolean
	 *
	 * @param bool $value Value
	 *
	 * @return void
	 */
	public function addBoolean($value) {
		$this->addParameter(new XMLRPCBoolParameter($value));
	}
}
