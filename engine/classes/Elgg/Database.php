<?php
namespace Elgg;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\Driver\ServerInfoAwareConnection;
use Doctrine\DBAL\Query\QueryBuilder;
use Elgg\Database\DbConfig;
use Psr\Log\LogLevel;

/**
 * The Elgg database
 *
 * @access private
 * @internal Use the public API functions in engine/lib/database.php
 *
 * @property-read string $prefix Elgg table prefix (read only)
 */
class Database {
	use Profilable;
	use Loggable;

	const DELAYED_QUERY = 'q';
	const DELAYED_TYPE = 't';
	const DELAYED_HANDLER = 'h';
	const DELAYED_PARAMS = 'p';

	/**
	 * @var string $table_prefix Prefix for database tables
	 */
	private $table_prefix;

	/**
	 * @var Connection[]
	 */
	private $connections = [];

	/**
	 * @var int $query_count The number of queries made
	 */
	private $query_count = 0;

	/**
	 * Query cache for select queries.
	 *
	 * Queries and their results are stored in this cache as:
	 * <code>
	 * $DB_QUERY_CACHE[query hash] => array(result1, result2, ... resultN)
	 * </code>
	 * @see \Elgg\Database::getResults() for details on the hash.
	 *
	 * @var \Elgg\Cache\LRUCache $query_cache The cache
	 */
	private $query_cache = null;

	/**
	 * @var int $query_cache_size The number of queries to cache
	 */
	private $query_cache_size = 50;

	/**
	 * Queries are saved as an array with the DELAYED_* constants as keys.
	 *
	 * @see registerDelayedQuery()
	 *
	 * @var array $delayed_queries Queries to be run during shutdown
	 */
	protected $delayed_queries = [];

	/**
	 * @var \Elgg\Database\DbConfig $config Database configuration
	 */
	private $config;

	/**
	 * Constructor
	 *
	 * @param DbConfig $config DB configuration
	 */
	public function __construct(DbConfig $config) {
		$this->resetConnections($config);
	}

	/**
	 * Reset the connections with new credentials
	 *
	 * @param DbConfig $config DB config
	 *
	 * @return void
	 */
	public function resetConnections(DbConfig $config) {
		$this->connections = [];
		$this->config = $config;
		$this->table_prefix = $config->getTablePrefix();
		$this->enableQueryCache();
	}

	/**
	 * Gets (if required, also creates) a DB connection.
	 *
	 * @param string $type The type of link we want: "read", "write" or "readwrite".
	 *
	 * @return Connection
	 * @throws \DatabaseException
	 * @access private
	 */
	public function getConnection($type) {
		if (isset($this->connections[$type])) {
			return $this->connections[$type];
		} else if (isset($this->connections['readwrite'])) {
			return $this->connections['readwrite'];
		} else {
			$this->setupConnections();
			return $this->getConnection($type);
		}
	}

	/**
	 * Establish database connections
	 *
	 * If the configuration has been set up for multiple read/write databases, set those
	 * links up separately; otherwise just create the one database link.
	 *
	 * @return void
	 * @throws \DatabaseException
	 * @access private
	 */
	public function setupConnections() {
		if ($this->config->isDatabaseSplit()) {
			$this->connect('read');
			$this->connect('write');
		} else {
			$this->connect('readwrite');
		}
	}

	/**
	 * Establish a connection to the database server
	 *
	 * Connect to the database server and use the Elgg database for a particular database link
	 *
	 * @param string $type The type of database connection. "read", "write", or "readwrite".
	 *
	 * @return void
	 * @throws \DatabaseException
	 * @access private
	 */
	public function connect($type = "readwrite") {
		$conf = $this->config->getConnectionConfig($type);

		$params = [
			'dbname' => $conf['database'],
			'user' => $conf['user'],
			'password' => $conf['password'],
			'host' => $conf['host'],
			'charset' => $conf['encoding'],
			'driver' => 'pdo_mysql',
		];

		try {
			$this->connections[$type] = DriverManager::getConnection($params);
			$this->connections[$type]->setFetchMode(\PDO::FETCH_OBJ);

			// https://github.com/Elgg/Elgg/issues/8121
			$sub_query = "SELECT REPLACE(@@SESSION.sql_mode, 'ONLY_FULL_GROUP_BY', '')";
			$this->connections[$type]->exec("SET SESSION sql_mode=($sub_query);");
		} catch (\Exception $e) {
			// http://dev.mysql.com/doc/refman/5.1/en/error-messages-server.html
			$this->log(LogLevel::ERROR, $e);

			if ($e->getCode() == 1102 || $e->getCode() == 1049) {
				$msg = "Elgg couldn't select the database '{$conf['database']}'. "
					. "Please check that the database is created and you have access to it.";
			} else {
				$msg = "Elgg couldn't connect to the database using the given credentials. Check the settings file.";
			}
			throw new \DatabaseException($msg);
		}
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
	 * @param QueryBuilder|string $query    The query being passed.
	 * @param callable            $callback Optionally, the name of a function to call back to on each row
	 * @param array               $params   Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
	 *
	 * @return array An array of database result objects or callback function results. If the query
	 *               returned nothing, an empty array.
	 * @throws \DatabaseException
	 */
	public function getData($query, $callback = null, array $params = []) {
		return $this->getResults($query, $callback, false, $params);
	}

	/**
	 * Retrieve a single row from the database.
	 *
	 * Similar to {@link \Elgg\Database::getData()} but returns only the first row
	 * matched.  If a callback function $callback is specified, the row will be passed
	 * as the only argument to $callback.
	 *
	 * @param QueryBuilder|string $query    The query to execute.
	 * @param callable            $callback A callback function to apply to the row
	 * @param array               $params   Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
	 *
	 * @return mixed A single database result object or the result of the callback function.
	 * @throws \DatabaseException
	 */
	public function getDataRow($query, $callback = null, array $params = []) {
		return $this->getResults($query, $callback, true, $params);
	}

	/**
	 * Insert a row into the database.
	 *
	 * @note Altering the DB invalidates all queries in the query cache.
	 *
	 * @param QueryBuilder|string $query  The query to execute.
	 * @param array               $params Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
	 *
	 * @return int|false The database id of the inserted row if a AUTO_INCREMENT field is
	 *                   defined, 0 if not, and false on failure.
	 * @throws \DatabaseException
	 */
	public function insertData($query, array $params = []) {

		if ($query instanceof QueryBuilder) {
			$params = $query->getParameters();
			$query = $query->getSQL();
		}

		if ($this->logger) {
			$this->logger->info("DB insert query $query (params: " . print_r($params, true) . ")");
		}

		$connection = $this->getConnection('write');

		$this->invalidateQueryCache();

		$this->executeQuery($query, $connection, $params);
		return (int) $connection->lastInsertId();
	}

	/**
	 * Update the database.
	 *
	 * @note Altering the DB invalidates all queries in the query cache.
	 *
	 * @note WARNING! update_data() has the 2nd and 3rd arguments reversed.
	 *
	 * @param QueryBuilder|string $query        The query to run.
	 * @param bool                $get_num_rows Return the number of rows affected (default: false).
	 * @param array               $params       Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
	 *
	 * @return bool|int
	 * @throws \DatabaseException
	 */
	public function updateData($query, $get_num_rows = false, array $params = []) {

		if ($query instanceof QueryBuilder) {
			$params = $query->getParameters();
			$query = $query->getSQL();
		}

		if ($this->logger) {
			$this->logger->info("DB update query $query (params: " . print_r($params, true) . ")");
		}

		$this->invalidateQueryCache();

		$stmt = $this->executeQuery($query, $this->getConnection('write'), $params);
		if ($get_num_rows) {
			return $stmt->rowCount();
		} else {
			return true;
		}
	}

	/**
	 * Delete data from the database
	 *
	 * @note Altering the DB invalidates all queries in query cache.
	 *
	 * @param QueryBuilder|string $query  The SQL query to run
	 * @param array               $params Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
	 *
	 * @return int The number of affected rows
	 * @throws \DatabaseException
	 */
	public function deleteData($query, array $params = []) {

		if ($query instanceof QueryBuilder) {
			$params = $query->getParameters();
			$query = $query->getSQL();
		}

		if ($this->logger) {
			$this->logger->info("DB delete query $query (params: " . print_r($params, true) . ")");
		}

		$connection = $this->getConnection('write');

		$this->invalidateQueryCache();

		$stmt = $this->executeQuery("$query", $connection, $params);
		return (int) $stmt->rowCount();
	}

	/**
	 * Get a string that uniquely identifies a callback during the current request.
	 *
	 * This is used to cache queries whose results were transformed by the callback. If the callback involves
	 * object method calls of the same class, different instances will return different values.
	 *
	 * @param callable $callback The callable value to fingerprint
	 *
	 * @return string A string that is unique for each callable passed in
	 * @since 1.9.4
	 * @access private
	 */
	protected function fingerprintCallback($callback) {
		if (is_string($callback)) {
			return $callback;
		}
		if (is_object($callback)) {
			return spl_object_hash($callback) . "::__invoke";
		}
		if (is_array($callback)) {
			if (is_string($callback[0])) {
				return "{$callback[0]}::{$callback[1]}";
			}
			return spl_object_hash($callback[0]) . "::{$callback[1]}";
		}
		// this should not happen
		return "";
	}

	/**
	 * Handles queries that return results, running the results through a
	 * an optional callback function. This is for R queries (from CRUD).
	 *
	 * @param QueryBuilder|string $query    The select query to execute
	 * @param string              $callback An optional callback function to run on each row
	 * @param bool                $single   Return only a single result?
	 * @param array               $params   Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
	 *
	 * @return array An array of database result objects or callback function results. If the query
	 *               returned nothing, an empty array.
	 * @throws \DatabaseException
	 */
	protected function getResults($query, $callback = null, $single = false, array $params = []) {

		if ($query instanceof QueryBuilder) {
			$params = $query->getParameters();
			$sql = $query->getSQL();
		} else {
			$sql = $query;
		}

		// Since we want to cache results of running the callback, we need to
		// namespace the query with the callback and single result request.
		// https://github.com/elgg/elgg/issues/4049
		$query_id = (int) $single . $sql . '|';
		if ($params) {
			$query_id .= serialize($params) . '|';
		}

		if ($callback) {
			if (!is_callable($callback)) {
				throw new \RuntimeException('$callback must be a callable function. Given '
											. _elgg_services()->handlers->describeCallable($callback));
			}
			$query_id .= $this->fingerprintCallback($callback);
		}

		// MD5 yields smaller mem usage for cache and cleaner logs
		$hash = md5($query_id);

		// Is cached?
		if ($this->query_cache) {
			if (isset($this->query_cache[$hash])) {
				if ($this->logger) {
					$this->logger->info("DB query results returned from cache (hash: $hash)");
				}
				return $this->query_cache[$hash];
			}
		}
		
		if ($this->logger) {
			$this->logger->info("DB select query $sql (params: " . print_r($params, true) . ")");
		}
		
		$return = [];

		if ($query instanceof QueryBuilder) {
			$stmt = $this->executeQuery($query, $query->getConnection());
		} else {
			$stmt = $this->executeQuery($query, $this->getConnection('read'), $params);
		}
		
		while ($row = $stmt->fetch()) {
			if ($callback) {
				$row = call_user_func($callback, $row);
			}

			if ($single) {
				$return = $row;
				break;
			} else {
				$return[] = $row;
			}
		}

		// Cache result
		if ($this->query_cache) {
			$this->query_cache[$hash] = $return;
			if ($this->logger) {
				$this->logger->info("DB query results cached (hash: $hash)");
			}
		}

		return $return;
	}

	/**
	 * Execute a query.
	 *
	 * $query is executed via {@link Connection::query}. If there is an SQL error,
	 * a {@link DatabaseException} is thrown.
	 *
	 * @param QueryBuilder|string $query      The query
	 * @param Connection          $connection The DB connection
	 * @param array               $params     Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
	 *
	 * @return Statement The result of the query
	 * @throws \DatabaseException
	 */
	protected function executeQuery($query, Connection $connection, array $params = []) {
		if ($query == null) {
			throw new \DatabaseException("Query cannot be null");
		}

		$sql = $query;
		if ($query instanceof QueryBuilder) {
			$params = $query->getParameters();
			$sql = $query->getSQL();
		}

		$this->query_count++;

		if ($this->timer) {
			$timer_key = preg_replace('~\\s+~', ' ', trim($sql . '|' . serialize($params)));
			$this->timer->begin(['SQL', $timer_key]);
		}

		try {
			if ($query instanceof QueryBuilder) {
				$value = $query->execute();
			} elseif ($params) {
				$value = $connection->executeQuery($sql, $params);
			} else {
				// faster
				$value = $connection->query($sql);
			}
		} catch (\Exception $e) {
			$ex = new \DatabaseException($e->getMessage());
			$ex->setParameters($params);
			$ex->setQuery($sql);

			throw $ex;
		}

		if ($this->timer) {
			$this->timer->end(['SQL', $timer_key]);
		}

		return $value;
	}

	/**
	 * Runs a full database script from disk.
	 *
	 * The file specified should be a standard SQL file as created by
	 * mysqldump or similar.  Statements must be terminated with ;
	 * and a newline character (\n or \r\n).
	 *
	 * The special string 'prefix_' is replaced with the database prefix
	 * as defined in {@link $this->tablePrefix}.
	 *
	 * @warning Only single line comments are supported. A comment
	 * must start with '-- ' or '# ', where the comment sign is at the
	 * very beginning of each line.
	 *
	 * @warning Errors do not halt execution of the script.  If a line
	 * generates an error, the error message is saved and the
	 * next line is executed.  After the file is run, any errors
	 * are displayed as a {@link DatabaseException}
	 *
	 * @param string $scriptlocation The full path to the script
	 *
	 * @return void
	 * @throws \DatabaseException
	 * @access private
	 */
	public function runSqlScript($scriptlocation) {
		$script = file_get_contents($scriptlocation);
		if ($script) {
			$errors = [];

			// Remove MySQL '-- ' and '# ' style comments
			$script = preg_replace('/^(?:--|#) .*$/m', '', $script);

			// Statements must end with ; and a newline
			$sql_statements = preg_split('/;[\n\r]+/', "$script\n");

			foreach ($sql_statements as $statement) {
				$statement = trim($statement);
				$statement = str_replace("prefix_", $this->table_prefix, $statement);
				if (!empty($statement)) {
					try {
						$this->updateData($statement);
					} catch (\DatabaseException $e) {
						$errors[] = $e->getMessage();
					}
				}
			}
			if (!empty($errors)) {
				$errortxt = "";
				foreach ($errors as $error) {
					$errortxt .= " {$error};";
				}

				$msg = "There were a number of issues: " . $errortxt;
				throw new \DatabaseException($msg);
			}
		} else {
			$msg = "Elgg couldn't find the requested database script at " . $scriptlocation . ".";
			throw new \DatabaseException($msg);
		}
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
		if ($type != 'read' && $type != 'write') {
			return false;
		}

		$this->delayed_queries[] = [
			self::DELAYED_QUERY => $query,
			self::DELAYED_TYPE => $type,
			self::DELAYED_HANDLER => $callback,
			self::DELAYED_PARAMS => $params,
		];

		return true;
	}

	/**
	 * Trigger all queries that were registered as "delayed" queries. This is
	 * called by the system automatically on shutdown.
	 *
	 * @return void
	 * @access private
	 * @todo make protected once this class is part of public API
	 */
	public function executeDelayedQueries() {

		foreach ($this->delayed_queries as $set) {
			$query = $set[self::DELAYED_QUERY];
			$type = $set[self::DELAYED_TYPE];
			$handler = $set[self::DELAYED_HANDLER];
			$params = $set[self::DELAYED_PARAMS];

			try {
				$stmt = $this->executeQuery($query, $this->getConnection($type), $params);

				if (is_callable($handler)) {
					call_user_func($handler, $stmt);
				}
			} catch (\Exception $e) {
				if ($this->logger) {
					// Suppress all exceptions since page already sent to requestor
					$this->logger->error($e);
				}
			}
		}

		$this->delayed_queries = [];
	}

	/**
	 * Enable the query cache
	 *
	 * This does not take precedence over the \Elgg\Database\Config setting.
	 *
	 * @return void
	 * @access private
	 */
	public function enableQueryCache() {
		if ($this->config->isQueryCacheEnabled() && $this->query_cache === null) {
			// @todo if we keep this cache, expose the size as a config parameter
			$this->query_cache = new \Elgg\Cache\LRUCache($this->query_cache_size);
		}
	}

	/**
	 * Disable the query cache
	 *
	 * This is useful for special scripts that pull large amounts of data back
	 * in single queries.
	 *
	 * @return void
	 * @access private
	 */
	public function disableQueryCache() {
		$this->query_cache = null;
	}

	/**
	 * Invalidate the query cache
	 *
	 * @return void
	 */
	protected function invalidateQueryCache() {
		if ($this->query_cache) {
			$this->query_cache->clear();
			if ($this->logger) {
				$this->logger->info("Query cache invalidated");
			}
		}
	}

	/**
	 * Get the number of queries made to the database
	 *
	 * @return int
	 * @access private
	 */
	public function getQueryCount() {
		return $this->query_count;
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
		$value = (int) $value;

		if ($signed === false) {
			if ($value < 0) {
				$value = 0;
			}
		}

		return $value;
	}

	/**
	 * Sanitizes a string for use in a query
	 *
	 * @param string $value Value to escape
	 * @return string
	 * @throws \DatabaseException
	 * @deprecated Use query parameters where possible
	 */
	public function sanitizeString($value) {
		if (is_array($value)) {
			throw new \DatabaseException(__METHOD__ . '() and serialize_string() cannot accept arrays.');
		}
		$quoted = $this->getConnection('read')->quote($value);
		if ($quoted[0] !== "'" || substr($quoted, -1) !== "'") {
			throw new \DatabaseException("PDO::quote did not return surrounding single quotes.");
		}
		return substr($quoted, 1, -1);
	}

	/**
	 * Get the server version number
	 *
	 * @param string $type Connection type (Config constants, e.g. Config::READ_WRITE)
	 *
	 * @return string Empty if version cannot be determined
	 * @access private
	 */
	public function getServerVersion($type) {
		$driver = $this->getConnection($type)->getWrappedConnection();
		if ($driver instanceof ServerInfoAwareConnection) {
			return $driver->getServerVersion();
		}

		return null;
	}

	/**
	 * Handle magic property reads
	 *
	 * @param string $name Property name
	 * @return mixed
	 */
	public function __get($name) {
		if ($name === 'prefix') {
			return $this->table_prefix;
		}

		throw new \RuntimeException("Cannot read property '$name'");
	}

	/**
	 * Handle magic property writes
	 *
	 * @param string $name  Property name
	 * @param mixed  $value Value
	 * @return void
	 */
	public function __set($name, $value) {
		throw new \RuntimeException("Cannot write property '$name'");
	}
}
