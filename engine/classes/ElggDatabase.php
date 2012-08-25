<?php

class ElggDatabase {
	
	public function __construct() {}
	
	/**
	 * Retrieve rows from the database.
	 *
	 * Queries are executed with {@link execute_query()} and results
	 * are retrieved with {@link mysql_fetch_object()}.  If a callback
	 * function $callback is defined, each row will be passed as the single
	 * argument to $callback.  If no callback function is defined, the
	 * entire result set is returned as an array.
	 *
	 * @param mixed  $query    The query being passed.
	 * @param string $callback Optionally, the name of a function to call back to on each row
	 *
	 * @return array An array of database result objects or callback function results. If the query
	 *               returned nothing, an empty array.
	 * @access private
	 */
	public function getData($query, $callback = '') {
		return elgg_query_runner($query, $callback, false);	
	}
	
	/**
	 * Retrieve a single row from the database.
	 *
	 * Similar to {@link ElggDatabase::getData()} but returns only the first row
	 * matched.  If a callback function $callback is specified, the row will be passed
	 * as the only argument to $callback.
	 *
	 * @param mixed  $query    The query to execute.
	 * @param string $callback A callback function
	 *
	 * @return mixed A single database result object or the result of the callback function.
	 * @access private
	 */
	public function getDataRow($query, $callback = '') {
		return elgg_query_runner($query, $callback, true);	
	}
}