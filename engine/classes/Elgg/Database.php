<?php

/**
 * An object representing a single Elgg database.
 *
 * WARNING: THIS API IS IN FLUX. PLUGIN AUTHORS SHOULD NOT USE. See lib/database.php instead.
 *
 * TODO: Convert query cache to a private local variable (or remove completely).
 * TODO: Convert delayed queries to private local variable.
 * TODO: Convert db link registry to private local variable.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Database
 */
class Elgg_Database {

	/** @var string $tablePrefix Prefix for database tables */
	private $tablePrefix;

	/**
	 * Constructor
	 */
	public function __construct() {
		global $CONFIG;

		$this->tablePrefix = $CONFIG->dbprefix;
	}

	/**
	 * Returns (if required, also creates) a database link resource.
	 *
	 * Database link resources are stored in the {@link $dblink} global.  These
	 * resources are created by {@link Elgg_Database::setupConnections()}, which
	 * is called if no links exist.
	 *
	 * @param string $type The type of link we want: "read", "write" or "readwrite".
	 *
	 * @return resource Database link
	 * @throws DatabaseException
	 * @todo make private or protected
	 */
	public function getLink($type) {
		global $dblink;

		if (isset($dblink[$type])) {
			return $dblink[$type];
		} else if (isset($dblink['readwrite'])) {
			return $dblink['readwrite'];
		} else {
			$this->setupConnections();
			return $this->getLink($type);
		}
	}

	/**
	 * Establish database connections
	 *
	 * If the configuration has been set up for multiple read/write databases, set those
	 * links up separately; otherwise just create the one database link.
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public function setupConnections() {
		global $CONFIG;

		if (!empty($CONFIG->db->split)) {
			$this->establishLink('read');
			$this->establishLink('write');
		} else {
			$this->establishLink('readwrite');
		}
	}


	/**
	 * Establish a connection to the database server
	 *
	 * Connect to the database server and use the Elgg database for a particular database link
	 *
	 * @param string $dblinkname The type of database connection. Used to identify the
	 * resource: "read", "write", or "readwrite".
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	public function establishLink($dblinkname = "readwrite") {
		global $CONFIG, $dblink, $DB_QUERY_CACHE, $dbcalls;

		if ($dblinkname != "readwrite" && isset($CONFIG->db[$dblinkname])) {
			if (is_array($CONFIG->db[$dblinkname])) {
				$index = rand(0, sizeof($CONFIG->db[$dblinkname]));
				$dbhost = $CONFIG->db[$dblinkname][$index]->dbhost;
				$dbuser = $CONFIG->db[$dblinkname][$index]->dbuser;
				$dbpass = $CONFIG->db[$dblinkname][$index]->dbpass;
				$dbname = $CONFIG->db[$dblinkname][$index]->dbname;
			} else {
				$dbhost = $CONFIG->db[$dblinkname]->dbhost;
				$dbuser = $CONFIG->db[$dblinkname]->dbuser;
				$dbpass = $CONFIG->db[$dblinkname]->dbpass;
				$dbname = $CONFIG->db[$dblinkname]->dbname;
			}
		} else {
			$dbhost = $CONFIG->dbhost;
			$dbuser = $CONFIG->dbuser;
			$dbpass = $CONFIG->dbpass;
			$dbname = $CONFIG->dbname;
		}

		// Connect to database
		if (!$dblink[$dblinkname] = mysql_connect($dbhost, $dbuser, $dbpass, true)) {
			$msg = "Elgg couldn't connect to the database using the given credentials. Check the settings file.";
			throw new DatabaseException($msg);
		}

		if (!mysql_select_db($dbname, $dblink[$dblinkname])) {
			$msg = "Elgg couldn't select the database '$dbname', please check that the database is created and you have access to it.";
			throw new DatabaseException($msg);
		}

		// Set DB for UTF8
		mysql_query("SET NAMES utf8");

		$db_cache_off = FALSE;
		if (isset($CONFIG->db_disable_query_cache)) {
			$db_cache_off = $CONFIG->db_disable_query_cache;
		}

		// Set up cache if global not initialized and query cache not turned off
		if ((!$DB_QUERY_CACHE) && (!$db_cache_off)) {
			// @todo if we keep this cache in 1.9, expose the size as a config parameter
			$DB_QUERY_CACHE = new Elgg_Cache_LRUCache(200);
		}
	}

	/**
	 * Retrieve rows from the database.
	 *
	 * Queries are executed with {@link Elgg_Database::executeQuery()} and results
	 * are retrieved with {@link mysql_fetch_object()}.  If a callback
	 * function $callback is defined, each row will be passed as a single
	 * argument to $callback.  If no callback function is defined, the
	 * entire result set is returned as an array.
	 *
	 * @param mixed  $query    The query being passed.
	 * @param string $callback Optionally, the name of a function to call back to on each row
	 *
	 * @return array An array of database result objects or callback function results. If the query
	 *               returned nothing, an empty array.
	 */
	public function getData($query, $callback = '') {
		return $this->getResults($query, $callback, false);
	}

	/**
	 * Retrieve a single row from the database.
	 *
	 * Similar to {@link Elgg_Database::getData()} but returns only the first row
	 * matched.  If a callback function $callback is specified, the row will be passed
	 * as the only argument to $callback.
	 *
	 * @param mixed  $query    The query to execute.
	 * @param string $callback A callback function
	 *
	 * @return mixed A single database result object or the result of the callback function.
	 */
	public function getDataRow($query, $callback = '') {
		return $this->getResults($query, $callback, true);
	}

	/**
	 * Insert a row into the database.
	 *
	 * @note Altering the DB invalidates all queries in the query cache.
	 *
	 * @param mixed $query The query to execute.
	 *
	 * @return int|false The database id of the inserted row if a AUTO_INCREMENT field is
	 *                   defined, 0 if not, and false on failure.
	 */
	public function insertData($query) {

		elgg_log("DB query $query", 'NOTICE');

		$dblink = $this->getLink('write');

		$this->invalidateQueryCache();

		if ($this->executeQuery("$query", $dblink)) {
			return mysql_insert_id($dblink);
		}

		return false;
	}

	/**
	 * Update the database.
	 *
	 * @note Altering the DB invalidates all queries in the query cache.
	 *
	 * @internal Not returning the number of rows updated as this depends on the
	 * type of update query and whether values were actually changed.
	 *
	 * @param string $query The query to run.
	 *
	 * @return bool
	 */
	public function updateData($query) {

		elgg_log("DB query $query", 'NOTICE');

		$dblink = $this->getLink('write');

		$this->invalidateQueryCache();

		return $this->executeQuery("$query", $dblink);
	}

	/**
	 * Delete data from the database
	 *
	 * @note Altering the DB invalidates all queries in query cache.
	 *
	 * @param string $query The SQL query to run
	 *
	 * @return int|false The number of affected rows or false on failure
	 */
	function deleteData($query) {

		elgg_log("DB query $query", 'NOTICE');

		$dblink = $this->getLink('write');

		$this->invalidateQueryCache();

		if ($this->executeQuery("$query", $dblink)) {
			return mysql_affected_rows($dblink);
		}

		return false;
	}


	/**
	 * Handles queries that return results, running the results through a
	 * an optional callback function. This is for R queries (from CRUD).
	 *
	 * @param string $query    The select query to execute
	 * @param string $callback An optional callback function to run on each row
	 * @param bool   $single   Return only a single result?
	 *
	 * @return array An array of database result objects or callback function results. If the query
	 *               returned nothing, an empty array.
	 */
	private function getResults($query, $callback = null, $single = false) {
		global $DB_QUERY_CACHE;

		// Since we want to cache results of running the callback, we need to
		// need to namespace the query with the callback and single result request.
		// http://trac.elgg.org/ticket/4049
		$callback_hash = is_object($callback) ? spl_object_hash($callback) : (string)$callback;
		$hash = $callback_hash . (int)$single . $query;

		// Is cached?
		if ($DB_QUERY_CACHE) {
			if (isset($DB_QUERY_CACHE[$hash])) {
				elgg_log("DB query $query results returned from cache (hash: $hash)", 'NOTICE');
				return $DB_QUERY_CACHE[$hash];
			}
		}

		$dblink = $this->getLink('read');
		$return = array();

		if ($result = $this->executeQuery("$query", $dblink)) {

			// test for callback once instead of on each iteration.
			// @todo check profiling to see if this needs to be broken out into
			// explicit cases instead of checking in the interation.
			$is_callable = is_callable($callback);
			while ($row = mysql_fetch_object($result)) {
				if ($is_callable) {
					$row = call_user_func($callback, $row);
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
	 * @param string   $query  The query
	 * @param resource $dblink The DB link
	 *
	 * @return resource|bool The result of mysql_query()
	 * @throws DatabaseException
	 */
	public function executeQuery($query, $dblink) {
		global $dbcalls;

		if ($query == NULL) {
			throw new DatabaseException("Invalid query");
		}

		if (!is_resource($dblink)) {
			throw new DatabaseException("Connection to database was lost.");
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

	/**
	 * Runs a full database script from disk.
	 *
	 * The file specified should be a standard SQL file as created by
	 * mysqldump or similar.  Statements must be terminated with ;
	 * and a newline character (\n or \r\n) with only one statement per line.
	 *
	 * The special string 'prefix_' is replaced with the database prefix
	 * as defined in {@link $this->tablePrefix}.
	 *
	 * @warning Errors do not halt execution of the script.  If a line
	 * generates an error, the error message is saved and the
	 * next line is executed.  After the file is run, any errors
	 * are displayed as a {@link DatabaseException}
	 *
	 * @param string $scriptlocation The full path to the script
	 *
	 * @return void
	 * @throws DatabaseException
	 */
	function runSqlScript($scriptlocation) {
		if ($script = file_get_contents($scriptlocation)) {
			global $CONFIG;

			$errors = array();

			// Remove MySQL -- style comments
			$script = preg_replace('/\-\-.*\n/', '', $script);

			// Statements must end with ; and a newline
			$sql_statements = preg_split('/;[\n\r]+/', $script);

			foreach ($sql_statements as $statement) {
				$statement = trim($statement);
				$statement = str_replace("prefix_", $this->tablePrefix, $statement);
				if (!empty($statement)) {
					try {
						$result = $this->updateData($statement);
					} catch (DatabaseException $e) {
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
				throw new DatabaseException($msg);
			}
		} else {
			$msg = "Elgg couldn't find the requested database script at " . $scriptlocation . ".";
			throw new DatabaseException($msg);
		}
	}

	/**
	 * Queue a query for execution upon shutdown.
	 *
	 * You can specify a handler function if you care about the result. This function will accept
	 * the raw result from {@link mysql_query()}.
	 *
	 * @param string   $query   The query to execute
	 * @param resource $dblink  The database link to use or the link type (read | write)
	 * @param string   $handler A callback function to pass the results array to
	 *
	 * @return boolean Whether registering was successful.
	 */
	function registerDelayedQuery($query, $dblink, $handler = "") {
		global $DB_DELAYED_QUERIES;

		if (!isset($DB_DELAYED_QUERIES)) {
			$DB_DELAYED_QUERIES = array();
		}

		if (!is_resource($dblink) && $dblink != 'read' && $dblink != 'write') {
			return false;
		}

		// Construct delayed query
		$delayed_query = array();
		$delayed_query['q'] = $query;
		$delayed_query['l'] = $dblink;
		$delayed_query['h'] = $handler;

		$DB_DELAYED_QUERIES[] = $delayed_query;

		return TRUE;
	}


	/**
	 * Trigger all queries that were registered as "delayed" queries. This is
	 * called by the system automatically on shutdown.
	 *
	 * @return void
	 */
	public function executeDelayedQueries() {
		global $DB_DELAYED_QUERIES;

		foreach ($DB_DELAYED_QUERIES as $query_details) {
			try {
				$link = $query_details['l'];

				if ($link == 'read' || $link == 'write') {
					$link = $this->getLink($link);
				} elseif (!is_resource($link)) {
					elgg_log("Link for delayed query not valid resource or db_link type. Query: {$query_details['q']}", 'WARNING');
				}

				$result = $this->executeQuery($query_details['q'], $link);

				if ((isset($query_details['h'])) && (is_callable($query_details['h']))) {
					$query_details['h']($result);
				}
			} catch (Exception $e) {
				// Suppress all errors since these can't be dealt with here
				elgg_log($e, 'WARNING');
			}
		}
	}

	/**
	 * Invalidate the query cache
	 *
	 * @return void
	 */
	public function invalidateQueryCache() {
		global $DB_QUERY_CACHE;
		if ($DB_QUERY_CACHE instanceof Elgg_Cache_LRUCache) {
			$DB_QUERY_CACHE->clear();
			elgg_log("Query cache invalidated", 'NOTICE');
		} else if ($DB_QUERY_CACHE) {
			// In case someone sets the cache to an array and primes it with data
			$DB_QUERY_CACHE = array();
			elgg_log("Query cache invalidated", 'NOTICE');
		}
	}

	/**
	 * Does the Elgg database exist?
	 *
	 * @return bool
	 */
	public function isInstalled() {
		global $CONFIG;

		if (isset($CONFIG->installed)) {
			return true;
		}

		try {
			$dblink = $this->getLink('read');
			mysql_query("SELECT value FROM {$this->tablePrefix}datalists WHERE name = 'installed'", $dblink);
			if (mysql_errno($dblink) > 0) {
				throw new DatabaseException();
			}
		} catch (DatabaseException $e) {
			throw new InstallationException("Unable to handle this request. This site is not configured or the database is down.");
		}

		$CONFIG->installed = true;

		return true;
	}
}
