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
	/** @return OrderByExpression */
	public function asc() {
		return new OrderByExpression($this, OrderByDirection::ASC);
	}
	
	/** @return OrderByExpression */
	public function desc() {
		return new OrderByExpression($this, OrderByDirection::DESC);
	}

	/**
	 * @param mixed $value A value expression
	 * 
	 * @return ComparisonExpression
	 */
	public function equals($value) {
		return new ComparisonExpression($this, ComparisonExpression::EQUALS, $value);
	}
}