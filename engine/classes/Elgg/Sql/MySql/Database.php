<?php
namespace Elgg\Sql\MySql;

use Elgg;
use Elgg\Sql;

/**
 * API IN FLUX. DO NOT USE DIRECTLY.
 * 
 * @package    Elgg.Core
 * @subpackage Sql
 * @since      1.11
 * 
 * @access private
 */
class Database implements Sql\Database {
	/**
	 * Constructor
	 * 
	 * @param Elgg\Database $elggDb
	 */
	public function __construct(Elgg\Database $elggDb) {
		$this->elggDb = $elggDb;
	}
	
	/** @inheritDoc */
	public function insertInto($tableName) {
		return new InsertStatement($elggDb);
	}
	
	/**
	 * Quote the given value for inclusion in an expression.
	 * 
	 * @param mixed $value
	 * 
	 * @param string
	 */
	private function quote($value) {
		if (is_int($value)) {
			return "$value";
		} else if (is_null($value)) {
			return "NULL";
		} else {
			$sanitizedValue = $this->elggDb->sanitizeString($value);
			
			return "'$sanitizedValue'";
		}
	}
}