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
interface WritableQuery extends Query {
	
	/**
	 * Deletes all the rows matching this query.
	 * 
	 * @return integer Number of rows affected. Throws on failure.
	 */
	public function delete(TableReference $ref);

	/**
	 * @inheritDoc
	 * @return WriteableQuery
	 */
	public function limit($count);
	
	/**
	 * Updates all the rows matching this query.
	 * 
	 * @param array $assignments Map of column names to values.
	 * 
	 * @return integer Number of rows affected. Throws on failure.
	 */
	public function update(array $assignments = array());

	/**
	 * @inheritDoc
	 * @return WritableQuery
	 */
	public function where(WhereExpression $where);
}