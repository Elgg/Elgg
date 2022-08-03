<?php

namespace Elgg\Application;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Elgg\Database as ElggDb;

/**
 * Elgg 3.0 public database API
 *
 * This is returned by elgg()->db or Application::start()->getDb().
 *
 * @property-read string $prefix Elgg table prefix (read only)
 */
class Database {

	/**
	 * The "real" database instance
	 *
	 * @var ElggDb
	 */
	private $db;

	/**
	 * Constructor
	 *
	 * @param ElggDb $db The Elgg database
	 */
	public function __construct(ElggDb $db) {
		$this->db = $db;
	}

	/**
	 * Retrieve rows from the database.
	 *
	 * Queries are executed with {@link \Elgg\Database::executeQuery()} and results
	 * are retrieved with {@link \PDO::fetchObject()}.  If a callback
	 * function $callback is defined, each row will be passed as a single
	 * argument to $callback.  If no callback function is defined, the
	 * entire result set is returned as an array.
	 *
	 * @param QueryBuilder $query    The query being passed.
	 * @param callable     $callback Optionally, the name of a function to call back to on each row
	 *
	 * @return array An array of database result objects or callback function results. If the query
	 *               returned nothing, an empty array.
	 */
	public function getData(QueryBuilder $query, $callback = '') {
		return $this->db->getData($query, $callback);
	}

	/**
	 * Retrieve a single row from the database.
	 *
	 * Similar to {@link \Elgg\Database::getData()} but returns only the first row
	 * matched.  If a callback function $callback is specified, the row will be passed
	 * as the only argument to $callback.
	 *
	 * @param QueryBuilder $query    The query to execute.
	 * @param callable     $callback A callback function to apply to the row
	 *
	 * @return mixed A single database result object or the result of the callback function.
	 */
	public function getDataRow(QueryBuilder $query, $callback = '') {
		return $this->db->getDataRow($query, $callback);
	}

	/**
	 * Insert a row into the database.
	 *
	 * @note Altering the DB invalidates all queries in the query cache.
	 *
	 * @param QueryBuilder $query The query to execute
	 *
	 * @return int|false The database id of the inserted row if a AUTO_INCREMENT field is
	 *                   defined, 0 if not, and false on failure.
	 */
	public function insertData(QueryBuilder $query) {
		return $this->db->insertData($query);
	}

	/**
	 * Update the database.
	 *
	 * @note Altering the DB invalidates all queries in the query cache.
	 *
	 * @param QueryBuilder $query      The query to run.
	 * @param bool         $getNumRows Return the number of rows affected (default: false).
	 *
	 * @return bool|int
	 */
	public function updateData(QueryBuilder $query, bool $getNumRows = false) {
		return $this->db->updateData($query, $getNumRows);
	}

	/**
	 * Delete data from the database
	 *
	 * @note Altering the DB invalidates all queries in query cache.
	 *
	 * @param QueryBuilder $query The SQL query to run
	 *
	 * @return int The number of affected rows
	 */
	public function deleteData(QueryBuilder $query): int {
		return $this->db->deleteData($query);
	}

	/**
	 * Gets (if required, also creates) a DB connection.
	 *
	 * @param string $type The type of link we want: "read", "write" or "readwrite".
	 *
	 * @return Connection
	 * @internal
	 */
	public function getConnection($type) {
		return $this->db->getConnection($type);
	}

	/**
	 * Queue a query for execution upon shutdown.
	 *
	 * You can specify a callback if you care about the result. This function will always
	 * be passed a \Doctrine\DBAL\Driver\Statement.
	 *
	 * @param QueryBuilder $query    The query to execute
	 * @param callable     $callback A callback function to pass the results array to
	 *
	 * @return void
	 * @internal
	 */
	public function registerDelayedQuery(QueryBuilder $query, $callback = null): void {
		$this->db->registerDelayedQuery($query, $callback);
	}

	/**
	 * Handle magic property reads
	 *
	 * @param string $name Property name
	 * @return mixed
	 */
	public function __get($name) {
		return $this->db->{$name};
	}

	/**
	 * Handle magic property writes
	 *
	 * @param string $name  Property name
	 * @param mixed  $value Value
	 * @return void
	 */
	public function __set($name, $value) {
		$this->db->{$name} = $value;
	}
}
