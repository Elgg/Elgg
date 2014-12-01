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
interface WhereExpression {
	/**
	 * @param WhereExpression $expr
	 * 
	 * @return WhereExpression
	 */
	public function or(WhereExpression $expr);
	
	/**
	 * @param WhereExpression $exprs
	 * 
	 * @return WhereExpression
	 */
	public function and(WhereExpression $expr);
}