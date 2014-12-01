<?php

/**
 * A reference to a particular table in a query.
 */
namespace Elgg\Sql;

/**
 * API IN FLUX. DO NOT USE DIRECTLY.
 * 
 * @package    Elgg.Core
 * @subpackage Sql
 * @since      1.11
 * 
 * @access private
 */
class TableReference {
	/** @var string */
	public $name;
	
	/**
	 * Constructor
	 * 
	 * @param string $name
	 */
	public function __construct($name) {
		$this->name = $name;
	}
	
	/**
	 * @param string $columnName
	 * 
	 * @return ColumnReference
	 */
	public function __get($columnName) {
		return new ColumnReference($this, $columnName);
	}
}