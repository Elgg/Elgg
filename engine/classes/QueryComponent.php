<?php
/**
 * @class QueryComponent Query component superclass.
 * Component of a query.
 * @see Query
 */
abstract class QueryComponent
{
	/**
	 * Associative array of fields and values
	 */
	private $fields;

	function __construct()
	{
		$this->fields = array();
	}

	/**
	 * Class member get overloading
	 *
	 * @param string $name
	 * @return mixed
	 */
	function __get($name) {
		return $this->fields[$name];
	}

	/**
	 * Class member set overloading
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	function __set($name, $value) {
		$this->fields[$name] = $value;

		return true;
	}
}
