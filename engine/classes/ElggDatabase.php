<?php

class ElggDatabase {
	
	private $tablePrefix;
	
	public function __construct() {
		global $CONFIG;
		
		$this->tablePrefix = $CONFIG->dbprefix;
	}
	
	/**
	 * Retrieve rows from the database.
	 *
	 * Queries are executed with {@link ElggDatabase::executeQuery()} and results
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
		return $this->queryRunner($query, $callback, false);	
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
		return $this->queryRunner($query, $callback, true);	
	}
	
	/**
	 * Insert a row into the database.
	 *
	 * @note Altering the DB invalidates all queries in {@link $DB_QUERY_CACHE}.
	 *
	 * @param mixed $query The query to execute.
	 *
	 * @return int|false The database id of the inserted row if a AUTO_INCREMENT field is
	 *                   defined, 0 if not, and false on failure.
	 * @access private
	 */
	public function insertData($query) {
		global $DB_QUERY_CACHE;

		elgg_log("DB query $query", 'NOTICE');
		
		$dblink = get_db_link('write');
	
		// Invalidate query cache
		if ($DB_QUERY_CACHE) {
			$DB_QUERY_CACHE->clear();
		}
	
		elgg_log("Query cache invalidated", 'NOTICE');
	
		if ($this->executeQuery("$query", $dblink)) {
			return mysql_insert_id($dblink);
		}
	
		return FALSE;
	}
	
	/**
	 * Update a row in the database.
	 *
	 * @note Altering the DB invalidates all queries in {@link $DB_QUERY_CACHE}.
	 *
	 * @param string $query The query to run.
	 *
	 * @return bool
	 * @access private
	 */
	public function updateData($query) {
		global $DB_QUERY_CACHE;

		elgg_log("DB query $query", 'NOTICE');
	
		$dblink = get_db_link('write');
	
		// Invalidate query cache
		if ($DB_QUERY_CACHE) {
			$DB_QUERY_CACHE->clear();
			elgg_log("Query cache invalidated", 'NOTICE');
		}
	
		return !!$this->executeQuery("$query", $dblink);
	}

	/**
	 * Remove a row from the database.
	 *
	 * @note Altering the DB invalidates all queries in {@link $DB_QUERY_CACHE}.
	 *
	 * @param string $query The SQL query to run
	 *
	 * @return int|false The number of affected rows or false on failure
	 * @access private
	 */
	function deleteData($query) {
		global $DB_QUERY_CACHE;
	
		elgg_log("DB query $query", 'NOTICE');
	
		$dblink = get_db_link('write');
	
		// Invalidate query cache
		if ($DB_QUERY_CACHE) {
			$DB_QUERY_CACHE->clear();
			elgg_log("Query cache invalidated", 'NOTICE');
		}
	
		if ($this->executeQuery("$query", $dblink)) {
			return mysql_affected_rows($dblink);
		}
	
		return FALSE;
	}


	/**
	 * Handles returning data from a query, running it through a callback function,
	 * and caching the results. This is for R queries (from CRUD).
	 *
	 * @access private
	 *
	 * @param string $query    The query to execute
	 * @param string $callback An optional callback function to run on each row
	 * @param bool   $single   Return only a single result?
	 *
	 * @return array An array of database result objects or callback function results. If the query
	 *               returned nothing, an empty array.
	 * @since 1.8.0
	 * @access private
	 */
	private function queryRunner($query, $callback = null, $single = false) {
		global $DB_QUERY_CACHE;
	
		// Since we want to cache results of running the callback, we need to
		// need to namespace the query with the callback and single result request.
		// http://trac.elgg.org/ticket/4049
		$callback_hash = is_object($callback) ? spl_object_hash($callback) : (string)$callback;
		$hash = $callback_hash . (int)$single . $query;
	
		// Is cached?
		if ($DB_QUERY_CACHE) {
			$cached_query = $DB_QUERY_CACHE[$hash];
	
			if ($cached_query !== FALSE) {
				elgg_log("DB query $query results returned from cache (hash: $hash)", 'NOTICE');
				return $cached_query;
			}
		}
	
		$dblink = get_db_link('read');
		$return = array();
	
		if ($result = $this->executeQuery("$query", $dblink)) {
	
			// test for callback once instead of on each iteration.
			// @todo check profiling to see if this needs to be broken out into
			// explicit cases instead of checking in the interation.
			$is_callable = is_callable($callback);
			while ($row = mysql_fetch_object($result)) {
				if ($is_callable) {
					$row = $callback($row);
				}
	
				if ($single) {
					$return = $row;
					break;
				} else {
					$return[] = $row;
				}
			}
		}
	
		if (empty($return)) {
			elgg_log("DB query $query returned no results.", 'NOTICE');
		}
	
		// Cache result
		if ($DB_QUERY_CACHE) {
			$DB_QUERY_CACHE[$hash] = $return;
			elgg_log("DB query $query results cached (hash: $hash)", 'NOTICE');
		}
	
		return $return;
	}
	
	/**
	 * Execute a query.
	 *
	 * $query is executed via {@link mysql_query()}.  If there is an SQL error,
	 * a {@link DatabaseException} is thrown.
	 *
	 * @internal
	 * {@link $dbcalls} is incremented and the query is saved into the {@link $DB_QUERY_CACHE}.
	 *
	 * @param string $query  The query
	 * @param link   $dblink The DB link
	 *
	 * @return The result of mysql_query()
	 * @throws DatabaseException
	 * @access private
	 */
	public function executeQuery($query, $dblink) {
		global $dbcalls;
	
		if ($query == NULL) {
			throw new DatabaseException(elgg_echo('DatabaseException:InvalidQuery'));
		}
	
		if (!is_resource($dblink)) {
			throw new DatabaseException(elgg_echo('DatabaseException:InvalidDBLink'));
		}
	
		$dbcalls++;
	
		$result = mysql_query($query, $dblink);
	
		if (mysql_errno($dblink)) {
			throw new DatabaseException(mysql_error($dblink) . "\n\n QUERY: " . $query);
		}
	
		return $result;
	}
	
	/**
	 * Return tables matching the database prefix {@link $this->tablePrefix}% in the currently
	 * selected database.
	 *
	 * @return array|false List of tables or false on failure
	 * @static array $tables Tables found matching the database prefix
	 * @access private
	 */
	public function getTables() {
		static $tables;
	
		if (isset($tables)) {
			return $tables;
		}
	
		try {
			$result = $this->getData("show tables like '{$this->tablePrefix}%'");
		} catch (DatabaseException $d) {
			// Likely we can't handle an exception here, so just return false.
			return FALSE;
		}
	
		$tables = array();
	
		if (is_array($result) && !empty($result)) {
			foreach ($result as $row) {
				$row = (array) $row;
				if (is_array($row) && !empty($row)) {
					foreach ($row as $element) {
						$tables[] = $element;
					}
				}
			}
		} else {
			return FALSE;
		}
	
		return $tables;
		
	}
	
}