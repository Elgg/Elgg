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
interface Query {
	
	/**
	 * @param Table    $table
	 * @param TableRef $ref   Joi
	 * @param 
	 */
	public function leftJoin(Table $table, TableRef &$ref), $columnName, $otherColumnName);
	
	/**
	 * Cap the number of rows affected by the query.
	 * 
	 * @param integer $count
	 * 
	 * @return WriteableQuery
	 */
	public function limit($count);
	
	/**
	 * Fetches all the rows matching this query.
	 * 
	 * @param mixed $selections Specification for what to return as the result.
	 * 
	 * @return Collection The results.
	 */
	public function select($columns);

	/**
	 * Places constraints on the rows affected by the query.
	 * 
	 * @return Query
	 */
	public function where(WhereExpression $where);
}n where(WhereExpression $where);
}
}