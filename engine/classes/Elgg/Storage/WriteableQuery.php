<?php

interface WritableQuery extends Query {
	
	/**
	 * @inheritDoc
	 * @return WritableQuery
	 */
	public function where(WhereExpression $where);
	
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
	 * Deletes all the rows matching this query.
	 * 
	 * @return integer Number of rows affected. Throws on failure.
	 */
	public function delete();

}