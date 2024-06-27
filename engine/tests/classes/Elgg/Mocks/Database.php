<?php

namespace Elgg\Mocks;

use Doctrine\DBAL\DriverManager;
use Elgg\Database as DbDatabase;
use Elgg\Exceptions\DatabaseException;
use Elgg\Mocks\Database\Result;

class Database extends DbDatabase {

	/**
	 * @var \stdClass[]
	 */
	protected $query_specs = [];

	/**
	 * @var int
	 */
	protected $last_insert_id = 0;

	/**
	 * {@inheritdoc}
	 */
	public function connect(string $type = 'readwrite'): void {
		$conf = $this->db_config->getConnectionConfig($type);
		
		$params = [
			'dbname' => $conf['database'],
			'user' => $conf['user'],
			'password' => $conf['password'],
			'host' => $conf['host'],
			'port' => $conf['port'],
			'charset' => $conf['encoding'],
			'driver' => 'pdo_mysql',
			'wrapperClass' => \Elgg\Mocks\Database\Connection::class,
		];
		
		try {
			$this->connections[$type] = DriverManager::getConnection($params);
			$this->connections[$type]->setDatabase($this);
		} catch (\Exception $e) {
			throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	 * Set the result of a query that may be called in the future
	 *
	 * @param array $spec Query spec
	 *
	 * @uses $spec['sql']       (string) required SQL
	 * @uses $spec['params']    (array) Optional params passed to query builder
	 * @uses $spec['results']   (callable|array) An array of result row objects,
	 *                                           or a callable that returns result rows (required for selects)
	 *                                           Callables receive the spec object as an argument
	 * @uses $spec['row_count'] (int) required for update/delete queries
	 * @uses $spec['insert_id'] (int) required for insert queries
	 * @uses $spec['times']     (int) how many times to match this spec (0 = unlimited)
	 *
	 * @return string ID of spec
	 */
	public function addQuerySpec(array $spec) {
		$default = [
			'params' => [],
			'results' => null,
			'row_count' => null,
			'insert_id' => null,
		];
		$spec = array_merge($default, $spec);

		$spec['sql'] = $this->normalizeSql($spec['sql']);

		if (!empty($spec['times'])) {
			$spec['remaining'] = $spec['times'];
		}
		unset($spec['times']);

		$hash = sha1(serialize([$spec['sql'], $spec['params']]));

		$this->query_specs[$hash] = (object) $spec;

		return $hash;
	}

	/**
	 * Remove a specified query added by addQuerySpec()
	 *
	 * @param int $id Query spec ID
	 *
	 * @return void
	 */
	public function removeQuerySpec($id) {
		unset($this->query_specs[$id]);
	}

	/**
	 * Remove all query specifications
	 *
	 * @return void
	 */
	public function clearQuerySpecs() {
		$this->query_specs = [];
	}

	/**
	 * Execute database query
	 *
	 * @param string $sql    Query
	 * @param array  $params Query params
	 *
	 * @return Result (statement)
	 */
	public function executeDatabaseQuery($sql, $params = []) {

		$sql = $this->normalizeSql($sql);
		$results = [];
		$row_count = 0;
		$this->last_insert_id = 0;

		$hash = sha1(serialize([$sql, $params]));
		$match = elgg_extract($hash, $this->query_specs);

		if ($match) {
			if (isset($match->results)) {
				if (is_callable($match->results)) {
					$results = call_user_func($match->results, $match);
				} else if (is_array($match->results)) {
					$results = $match->results;
				}
			}
			$row_count = isset($match->row_count) ? $match->row_count : count($results);
			if (isset($match->insert_id)) {
				$this->last_insert_id = $match->insert_id;
			}

			if (isset($match->remaining)) {
				$match->remaining--;
				if (!$match->remaining) {
					// don't allow more matches
					unset($this->query_specs[$hash]);
				}
			}
		}

		if (!$match && strpos($sql, 'select') !== 0) {
			// We need to make sure all UPDATE, INSERT and DELETE queries are
			// mocked, otherwise we will be getting incorrect test results
			throw new DatabaseException(
				"No testing query spec was found:" . PHP_EOL .
				"Query: " . $sql . PHP_EOL .
				"Params: " . var_export($params, true)
			);
		}

		return new Result(null, null, $results, (int) $row_count);
	}
	
	public function executeDatabaseStatement($sql, $params = []) {
		$result = $this->executeDatabaseQuery($sql, $params);
		return $result->rowCount();
	}
	
	public function getLastInsertId() {
		return $this->last_insert_id;
	}

	/**
	 * Attempt to normalize whitespace in a query
	 *
	 * @param string $query Query
	 *
	 * @return string
	 */
	protected function normalizeSql($query) {
		$query = trim($query);
		$query = str_replace('  ', ' ', $query);
		$query = strtolower($query);
		$query = preg_replace('~\\s+~', ' ', $query);

		return $query;
	}

	/**
	 * Get delayed query queue
	 * @return array
	 */
	public function reflectDelayedQueries() {
		return $this->delayed_queries;
	}
}
