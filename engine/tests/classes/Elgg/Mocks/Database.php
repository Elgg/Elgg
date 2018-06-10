<?php

namespace Elgg\Mocks;

use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Elgg\BaseTestCase;
use Elgg\Database as DbDatabase;
use Elgg\Database\DbConfig;
use Psr\Log\LoggerInterface;

class Database extends DbDatabase {

	/**
	 * @var \stdClass[]
	 */
	private $query_specs = [];

	/**
	 * @var int
	 */
	public $last_insert_id = null;

	/**
	 *
	 * @var BaseTestCase
	 */
	private $test;

	/**
	 * {@inheritdoc}
	 */
	public function __construct(DbConfig $config, LoggerInterface $logger = null) {
		parent::__construct($config);
		$this->setLogger($logger);
	}

	/**
	 * {@inheritdoc}
	 */
	public function setupConnections() {

	}

	/**
	 * {@inheritdoc}
	 */
	public function connect($type = "readwrite") {

	}

	/**
	 * {@inheritdoc}
	 */
	public function getConnection($type) {
		$connection = BaseTestCase::$_instance->getConnectionMock();

		$connection->expects(BaseTestCase::$_instance->any())
			->method('query')
			->will(BaseTestCase::$_instance->returnCallback([$this, 'executeDatabaseQuery']));

		$connection->expects(BaseTestCase::$_instance->any())
			->method('executeQuery')
			->will(BaseTestCase::$_instance->returnCallback([$this, 'executeDatabaseQuery']));

		$connection->expects(BaseTestCase::$_instance->any())
			->method('lastInsertId')
			->will(BaseTestCase::$_instance->returnCallback(function () {
				return $this->last_insert_id;
			}));

		$expression_builder = new ExpressionBuilder($connection);

		$connection->expects(BaseTestCase::$_instance->any())
			->method('getExpressionBuilder')
			->willReturn($expression_builder);

		$connection->expects(BaseTestCase::$_instance->any())
			->method('quote')
			->will(BaseTestCase::$_instance->returnCallback(function ($input, $type = null) {
				return "'" . $input . "''";
			}));


		return $connection;
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
		static $id = 0;

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
	 * @return MockObject (statement)
	 */
	public function executeDatabaseQuery($sql, $params = []) {

		$sql = $this->normalizeSql($sql);
		$results = [];
		$row_count = 0;
		$this->last_insert_id = null;

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
			throw new \DatabaseException(
				"No testing query spec was found:" . PHP_EOL .
				"Query: " . $sql . PHP_EOL .
				"Params: " . var_export($params, true)
			);
		}

		$statement = BaseTestCase::$_instance->getMockBuilder(\Doctrine\DBAL\PDOStatement::class)
			->setMethods([
				'fetch',
				'rowCount',
			])
			->disableOriginalConstructor()
			->getMock();

		$statement->expects(BaseTestCase::$_instance->any())
			->method('fetch')
			->will(BaseTestCase::$_instance->returnCallback(function () use (&$results) {
				return array_shift($results);
			}));

		$statement->expects(BaseTestCase::$_instance->any())
			->method('rowCount')
			->will(BaseTestCase::$_instance->returnValue($row_count));

		return $statement;
	}

	/**
	 * {@inheritdoc}
	 */
	public function sanitizeString($value) {
		return addslashes($value);
	}

	/**
	 * Attempt to normalize whitespace in a query
	 *
	 * @param string $query Query
	 *
	 * @return string
	 */
	private function normalizeSql($query) {
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
