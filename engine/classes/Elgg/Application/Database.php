<?php
namespace Elgg\Application;

use DatabaseException;
use Doctrine\DBAL\Connection;
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
	 * @access private
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
	 * @param string   $query    The query being passed.
	 * @param callable $callback Optionally, the name of a function to call back to on each row
	 * @param array    $params   Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
	 *
	 * @return array An array of database result objects or callback function results. If the query
	 *               returned nothing, an empty array.
	 * @throws DatabaseException
	 */
	public function getData($query, $callback = '', array $params = []) {
		return $this->db->getData($query, $callback, $params);
	}

	/**
	 * Retrieve a single row from the database.
	 *
	 * Similar to {@link \Elgg\Database::getData()} but returns only the first row
	 * matched.  If a callback function $callback is specified, the row will be passed
	 * as the only argument to $callback.
	 *
	 * @param string   $query    The query to execute.
	 * @param callable $callback A callback function to apply to the row
	 * @param array    $params   Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
	 *
	 * @return mixed A single database result object or the result of the callback function.
	 * @throws DatabaseException
	 */
	public function getDataRow($query, $callback = '', array $params = []) {
		return $this->db->getDataRow($query, $callback, $params);
	}

	/**
	 * Insert a row into the database.
	 *
	 * @note Altering the DB invalidates all queries in the query cache.
	 *
	 * @param string $query  The query to execute.
	 * @param array  $params Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
	 *
	 * @return int|false The database id of the inserted row if a AUTO_INCREMENT field is
	 *                   defined, 0 if not, and false on failure.
	 * @throws DatabaseException
	 */
	public function insertData($query, array $params = []) {
		return $this->db->insertData($query, $params);
	}

	/**
	 * Update the database.
	 *
	 * @note Altering the DB invalidates all queries in the query cache.
	 *
	 * @note WARNING! update_data() has the 2nd and 3rd arguments reversed.
	 *
	 * @param string $query      The query to run.
	 * @param bool   $getNumRows Return the number of rows affected (default: false).
	 * @param array  $params     Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
	 *
	 * @return bool|int
	 * @throws DatabaseException
	 */
	public function updateData($query, $getNumRows = false, array $params = []) {
		return $this->db->updateData($query, $getNumRows, $params);
	}

	/**
	 * Delete data from the database
	 *
	 * @note Altering the DB invalidates all queries in query cache.
	 *
	 * @param string $query  The SQL query to run
	 * @param array  $params Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
	 *
	 * @return int The number of affected rows
	 * @throws DatabaseException
	 */
	public function deleteData($query, array $params = []) {
		return $this->db->deleteData($query, $params);
	}

	/**
	 * Sanitizes an integer value for use in a query
	 *
	 * @param int  $value  Value to sanitize
	 * @param bool $signed Whether negative values are allowed (default: true)
	 * @return int
	 * @deprecated Use query parameters where possible
	 */
	public function sanitizeInt($value, $signed = true) {
		return $this->db->sanitizeInt($value, $signed);
	}

	/**
	 * Sanitizes a string for use in a query
	 *
	 * @param string $value Value to escape
	 * @return string
	 * @throws DatabaseException
	 * @deprecated Use query parameters where possible
	 */
	public function sanitizeString($value) {
		return $this->db->sanitizeString($value);
	}

	/**
	 * Gets (if required, also creates) a DB connection.
	 *
	 * @param string $type The type of link we want: "read", "write" or "readwrite".
	 *
	 * @return Connection
	 * @throws DatabaseException
	 * @access private
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
	 * @param string   $query    The query to execute
	 * @param string   $type     The query type ('read' or 'write')
	 * @param callable $callback A callback function to pass the results array to
	 * @param array    $params   Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
	 *
	 * @return boolean Whether registering was successful.
	 * @access private
	 */
	public function registerDelayedQuery($query, $type, $callback = null, array $params = []) {
		return $this->db->registerDelayedQuery($query, $type, $callback, $params);
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
