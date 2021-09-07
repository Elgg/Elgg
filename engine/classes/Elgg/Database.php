<?php

namespace Elgg;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Driver\ServerInfoAwareConnection;
use Doctrine\DBAL\Result;
use Doctrine\DBAL\Query\QueryBuilder;
use Elgg\Cache\QueryCache;
use Elgg\Database\DbConfig;
use Elgg\Exceptions\DatabaseException;
use Elgg\Traits\Debug\Profilable;
use Elgg\Traits\Loggable;
use Psr\Log\LogLevel;

/**
 * The Elgg database
 *
 * @internal
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
	 * @var \Elgg\Cache\QueryCache $query_cache The cache
	 */
	protected $query_cache;

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
	 * @param DbConfig   $config      DB configuration
	 * @param QueryCache $query_cache Query Cache
	 */
	public function __construct(DbConfig $config, QueryCache $query_cache) {
		$this->query_cache = $query_cache;
		
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
		$this->query_cache->enable();
		$this->query_cache->clear();
		
	}

	/**
	 * Gets (if required, also creates) a DB connection.
	 *
	 * @param string $type The type of link we want: "read", "write" or "readwrite".
	 *
	 * @return Connection
	 */
	public function getConnection(string $type): Connection {
		if (isset($this->connections[$type])) {
			return $this->connections[$type];
		} else if (isset($this->connections['readwrite'])) {
			return $this->connections['readwrite'];
		}
		
		$this->setupConnections();
		
		return $this->getConnection($type);
	}

	/**
	 * Establish database connections
	 *
	 * If the configuration has been set up for multiple read/write databases, set those
	 * links up separately; otherwise just create the one database link.
	 *
	 * @return void
	 */
	public function setupConnections(): void {
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
	 * @throws DatabaseException
	 */
	public function connect(string $type = 'readwrite'): void {
		$conf = $this->config->getConnectionConfig($type);

		$params = [
			'dbname' => $conf['database'],
			'user' => $conf['user'],
			'password' => $conf['password'],
			'host' => $conf['host'],
			'port' => $conf['port'],
			'charset' => $conf['encoding'],
			'driver' => 'pdo_mysql',
		];

		try {
			$this->connections[$type] = DriverManager::getConnection($params);

			// https://github.com/Elgg/Elgg/issues/8121
			$sub_query = "SELECT REPLACE(@@SESSION.sql_mode, 'ONLY_FULL_GROUP_BY', '')";
			$this->connections[$type]->executeStatement("SET SESSION sql_mode=($sub_query);");
		} catch (\Exception $e) {
			// http://dev.mysql.com/doc/refman/5.1/en/error-messages-server.html
			$this->log(LogLevel::ERROR, $e);

			if ($e->getCode() == 1102 || $e->getCode() == 1049) {
				$msg = "Elgg couldn't select the database '{$conf['database']}'. "
					. "Please check that the database is created and you have access to it.";
			} else {
				$msg = "Elgg couldn't connect to the database using the given credentials. Check the settings file.";
			}
			throw new DatabaseException($msg);
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
	 * @param array               $params   Query params. E.g. [1, 'steve'] or ['id' => 1, 'name' => 'steve']
	 *
	 * @return array An array of database result objects or callback function results. If the query
	 *               returned nothing, an empty array.
	 */
	public function getData($query, $callback = null, array $params = []) {
		if (!$query instanceof QueryBuilder) {
			$this->logDeprecatedMessage('The use of non ' . QueryBuilder::class . ' queries in ' . __METHOD__ . ' has been deprecated', '4.0');
		}

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
	 * @param array               $params   Query params. E.g. [1, 'steve'] or ['id' => 1, 'name' => 'steve']
	 *
	 * @return mixed A single database result object or the result of the callback function.
	 */
	public function getDataRow($query, $callback = null, array $params = []) {
		if (!$query instanceof QueryBuilder) {
			$this->logDeprecatedMessage('The use of non ' . QueryBuilder::class . ' queries in ' . __METHOD__ . ' has been deprecated', '4.0');
		}

		return $this->getResults($query, $callback, true, $params);
	}

	/**
	 * Insert a row into the database.
	 *
	 * @note Altering the DB invalidates all queries in the query cache.
	 *
	 * @param QueryBuilder|string $query  The query to execute.
	 * @param array               $params Query params. E.g. [1, 'steve'] or ['id' => 1, 'name' => 'steve']
	 *
	 * @return int The database id of the inserted row if a AUTO_INCREMENT field is defined, 0 if not
	 */
	public function insertData($query, array $params = []): int {
		if (!$query instanceof QueryBuilder) {
			$this->logDeprecatedMessage('The use of non ' . QueryBuilder::class . ' queries in ' . __METHOD__ . ' has been deprecated', '4.0');
		}

		$sql = $query;
		$connection = $this->getConnection('write');
		if ($query instanceof QueryBuilder) {
			$params = $query->getParameters();
			$sql = $query->getSQL();
			$connection = $query->getConnection();
		}

		$this->getLogger()->info("DB insert query {$sql} (params: " . print_r($params, true) . ")");

		$this->query_cache->clear();

		$this->executeQuery($query, $connection, $params);
		return (int) $connection->lastInsertId();
	}

	/**
	 * Update the database.
	 *
	 * @note Altering the DB invalidates all queries in the query cache.
	 *
	 * @param QueryBuilder|string $query        The query to run.
	 * @param bool                $get_num_rows Return the number of rows affected (default: false).
	 * @param array               $params       Query params. E.g. [1, 'steve'] or ['id' => 1, 'name' => 'steve']
	 *
	 * @return bool|int
	 */
	public function updateData($query, bool $get_num_rows = false, array $params = []) {
		if (!$query instanceof QueryBuilder) {
			$this->logDeprecatedMessage('The use of non ' . QueryBuilder::class . ' queries in ' . __METHOD__ . ' has been deprecated', '4.0');
		}

		$sql = $query;
		if ($query instanceof QueryBuilder) {
			$params = $query->getParameters();
			$sql = $query->getSQL();
		}

		$this->getLogger()->info("DB update query {$sql} (params: " . print_r($params, true) . ")");

		$this->query_cache->clear();

		$result = $this->executeQuery($query, $this->getConnection('write'), $params);
		if (!$get_num_rows) {
			return true;
		}

		return ($result instanceof Result) ? $result->rowCount() : $result;
	}

	/**
	 * Delete data from the database
	 *
	 * @note Altering the DB invalidates all queries in query cache.
	 *
	 * @param QueryBuilder|string $query  The SQL query to run
	 * @param array               $params Query params. E.g. [1, 'steve'] or ['id' => 1, 'name' => 'steve']
	 *
	 * @return int The number of affected rows
	 */
	public function deleteData($query, array $params = []): int {
		if (!$query instanceof QueryBuilder) {
			$this->logDeprecatedMessage('The use of non ' . QueryBuilder::class . ' queries in ' . __METHOD__ . ' has been deprecated', '4.0');
		}

		$sql = $query;
		if ($query instanceof QueryBuilder) {
			$params = $query->getParameters();
			$sql = $query->getSQL();
		}

		$this->getLogger()->info("DB delete query {$sql} (params: " . print_r($params, true) . ")");

		$this->query_cache->clear();

		$result = $this->executeQuery($query, $this->getConnection('write'), $params);
		return ($result instanceof Result) ? $result->rowCount() : $result;
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
	 */
	protected function fingerprintCallback($callback): string {
		if (is_string($callback)) {
			return $callback;
		}

		if (is_object($callback)) {
			return spl_object_hash($callback) . '::__invoke';
		}

		if (is_array($callback)) {
			if (is_string($callback[0])) {
				return "{$callback[0]}::{$callback[1]}";
			}

			return spl_object_hash($callback[0]) . "::{$callback[1]}";
		}

		// this should not happen
		return '';
	}

	/**
	 * Handles queries that return results, running the results through a
	 * an optional callback function. This is for R queries (from CRUD).
	 *
	 * @param QueryBuilder|string $query    The select query to execute
	 * @param callable            $callback An optional callback function to run on each row
	 * @param bool                $single   Return only a single result?
	 * @param array               $params   Query params. E.g. [1, 'steve'] or ['id' => 1, 'name' => 'steve']
	 *
	 * @return array|\stdClass An array of database result objects or callback function results. If the query
	 *               returned nothing, an empty array.
	 * @throws \RuntimeException
	 */
	protected function getResults($query, $callback = null, bool $single = false, array $params = []) {

		if ($query instanceof QueryBuilder) {
			$params = $query->getParameters();
			$sql = $query->getSQL();
		} else {
			$sql = $query;
		}
		
		// Since we want to cache results of running the callback, we need to
		// namespace the query with the callback and single result request.
		// https://github.com/elgg/elgg/issues/4049
		$extras = (int) $single . '|';
		if ($callback) {
			if (!is_callable($callback)) {
				throw new \RuntimeException('$callback must be a callable function. Given '
											. _elgg_services()->handlers->describeCallable($callback));
			}
			$extras .= $this->fingerprintCallback($callback);
		}
		
		$hash = $this->query_cache->getHash($sql, $params, $extras);

		$cached_results = $this->query_cache->get($hash);
		if (isset($cached_results)) {
			return $cached_results;
		}
		
		$this->getLogger()->info("DB select query {$sql} (params: " . print_r($params, true) . ")");
		
		$return = [];

		if ($query instanceof QueryBuilder) {
			$stmt = $this->executeQuery($query, $query->getConnection());
		} else {
			$stmt = $this->executeQuery($query, $this->getConnection('read'), $params);
		}
		
		while ($row = $stmt->fetchAssociative()) {
			$row_obj = (object) $row;
			if ($callback) {
				$row_obj = call_user_func($callback, $row_obj);
			}

			if ($single) {
				$return = $row_obj;
				break;
			} else {
				$return[] = $row_obj;
			}
		}

		// Cache result
		$this->query_cache->set($hash, $return);
				
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
	 * @param array               $params     Query params. E.g. [1, 'steve'] or ['id' => 1, 'name' => 'steve']
	 *
	 * @return Result|int The result of the query
	 * @throws DatabaseException
	 */
	protected function executeQuery($query, Connection $connection, array $params = []) {
		if ($query == null) {
			throw new DatabaseException("Query cannot be null");
		}

		$sql = $query;
		if ($query instanceof QueryBuilder) {
			$params = $query->getParameters();
			$sql = $query->getSQL();
		}
		
		foreach ($params as $param_key => $value) {
			if (substr($param_key, 0, 1) !== ':') {
				continue;
			}
			
			$this->getLogger()->warning('Unsupported colon detected in named param: ' . $param_key);
			
			unset($params[$param_key]);
			$params[substr($param_key, 1)] = $value;
		}
				
		try {
			$result = $this->trackQuery($sql, $params, function() use ($query, $params, $connection, $sql) {
				if ($query instanceof \Elgg\Database\QueryBuilder) {
					return $query->execute(false);
				} elseif ($query instanceof QueryBuilder) {
					return $query->execute();
				} elseif (!empty($params)) {
					return $connection->executeQuery($sql, $params);
				} else {
					// faster
					return $connection->executeQuery($sql);
				}
			});
		} catch (\Exception $e) {
			$ex = new DatabaseException($e->getMessage(), null, $e);
			$ex->setParameters($params);
			$ex->setQuery($sql);

			throw $ex;
		}

		return $result;
	}
	
	/**
	 * Tracks the query count and timers for a given query
	 *
	 * @param QueryBuilder|string $query    The query
	 * @param array               $params   Optional query params
	 * @param callable            $callback Callback to execyte during query execution
	 *
	 * @return mixed
	 */
	public function trackQuery($query, array $params, callable $callback) {
	
		$sql = $query;
		if ($query instanceof QueryBuilder) {
			$params = $query->getParameters();
			$sql = $query->getSQL();
		}

		$this->query_count++;

		$timer_key = false;
		if ($this->timer) {
			$timer_key = preg_replace('~\\s+~', ' ', trim($sql . '|' . serialize($params)));
			$this->timer->begin(['SQL', $timer_key]);
		}
		
		$stop_timer = function() use ($timer_key) {
			if ($timer_key) {
				$this->timer->end(['SQL', $timer_key]);
			}
		};
		
		try {
			$result = $callback();
		} catch (\Exception $e) {
			$stop_timer();
			
			throw $e;
		}
		
		$stop_timer();
		
		return $result;
	}

	/**
	 * Queue a query for execution upon shutdown.
	 *
	 * You can specify a callback if you care about the result. This function will always
	 * be passed a \Doctrine\DBAL\Driver\Statement.
	 *
	 * @param QueryBuilder|string $query    The query to execute
	 * @param string              $type     The query type ('read' or 'write')
	 * @param callable            $callback A callback function to pass the results array to
	 * @param array               $params   Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
	 *
	 * @return bool Whether registering was successful
	 */
	public function registerDelayedQuery($query, string $type, $callback = null, array $params = []): bool {
		if (!$query instanceof QueryBuilder) {
			$this->logDeprecatedMessage('The use of non ' . QueryBuilder::class . ' queries in ' . __METHOD__ . ' has been deprecated', '4.0');
		}

		if ($type !== 'read' && $type !== 'write') {
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
	 */
	public function executeDelayedQueries(): void {

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
				// Suppress all exceptions since page already sent to requestor
				$this->getLogger()->error($e);
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
	 */
	public function enableQueryCache(): void {
		$this->query_cache->enable();
	}

	/**
	 * Disable the query cache
	 *
	 * This is useful for special scripts that pull large amounts of data back
	 * in single queries.
	 *
	 * @return void
	 */
	public function disableQueryCache(): void {
		$this->query_cache->disable();
	}

	/**
	 * Get the number of queries made to the database
	 *
	 * @return int
	 */
	public function getQueryCount(): int {
		return $this->query_count;
	}

	/**
	 * Get the server version number
	 *
	 * @param string $type Connection type (Config constants, e.g. Config::READ_WRITE)
	 *
	 * @return string Empty if version cannot be determined
	 */
	public function getServerVersion(string $type): string {
		$driver = $this->getConnection($type)->getWrappedConnection();
		if ($driver instanceof ServerInfoAwareConnection) {
			return $driver->getServerVersion();
		}

		return '';
	}

	/**
	 * Handle magic property reads
	 *
	 * @param string $name Property name
	 *
	 * @return mixed
	 * @throws \RuntimeException
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
	 *
	 * @return void
	 * @throws \RuntimeException
	 */
	public function __set($name, $value): void {
		throw new \RuntimeException("Cannot write property '$name'");
	}
}
