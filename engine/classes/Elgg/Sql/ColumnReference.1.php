<?php
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
class ColumnReference {
	/**
	 * @param mixed $value A value expression
	 * 
	 * @return Comparison\Equals
	 */
	public function equals($value) {
		return new Comparison\Equals($this, $value);
	}
}