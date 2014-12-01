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
interface JoinExpression {
	/**
	 * Completes the join expression and allows continued evaluation of the query.
	 * 
	 * @param WhereExpression $expr
	 * 
	 * @return Query
	 */
	public function on(WhereExpression $expr);
}