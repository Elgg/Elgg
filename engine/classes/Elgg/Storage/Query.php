<?php


interface Query {
	/**
	 * Places constraints on the rows affected by the query.
	 * 
	 * @return Query
	 */
	public function where(WhereExpression $where);
	
	/**
	 * 
	 */
	public function leftJoin(Table $table, TableRef &$ref, $columnName, $otherColumnName);
	
	
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
}