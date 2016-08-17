<?php

namespace Elgg\Mocks;

use Doctrine\DBAL\Connection;
use Elgg\Database as DbDatabase;
use Elgg\Database\Config;
use Elgg\Logger;
use Elgg\TestCase;

class Database extends DbDatabase {

	/**
	 * @var stdClass[]
	 */
	private $query_specs = [];

	/**
	 * @var int
	 */
	public $last_insert_id = null;

	/**
	 *
	 * @var TestCase
	 */
	private $test;

	/**
	 * {@inheritdoc}
	 */
	public function __construct(Config $config, Logger $logger = null) {
		parent::__construct($config, $logger);
		$this->test = TestCase::getInstance();
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
		$connection = $this->test->getMockBuilder(Connection::class)
				->setMethods([
					'query',
					'executeQuery',
					'lastInsertId',
				])
				->disableOriginalConstructor()
				->getMock();

		$connection->expects($this->test->any())
				->method('query')
				->will($this->test->returnCallback([$this, 'executeDatabaseQuery']));

		$connection->expects($this->test->any())
				->method('executeQuery')
				->will($this->test->returnCallback([$this, 'executeDatabaseQuery']));

		$connection->expects($this->test->any())
				->method('lastInsertId')
				->will($this->test->returnCallback(function() {
							return $this->last_insert_id;
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
	 * @return int ID of spec
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

		$id++;

		$this->query_specs[$id] = (object) $spec;
		return $id;
	}

	/**
	 * Remove a specified query added by addQuerySpec()
	 *
	 * @param int $id Query spec ID
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
	 * @return PHPUnit_Framework_MockObject_MockObject (statement)
	 */
	public function executeDatabaseQuery($sql, $params = []) {

		$sql = $this->normalizeSql($sql);
		$results = [];
		$row_count = 0;
		$this->last_insert_id = null;

		$match = false;
		foreach ($this->query_specs as $i => $spec) {
			if ($spec->sql == $sql && $params == $spec->params) {
				$match = true;
				if (isset($spec->results)) {
					if (is_callable($spec->results)) {
						$results = call_user_func($spec->results, $spec);
					} else if (is_array($spec->results)) {
						$results = $spec->results;
					}
				}
				$row_count = isset($spec->row_count) ? $spec->row_count : count($results);
				if (isset($spec->insert_id)) {
					$this->last_insert_id = $spec->insert_id;
				}

				if (isset($spec->remaining)) {
					$spec->remaining--;
					if (!$spec->remaining) {
						// don't allow more matches
						unset($this->query_specs[$i]);
					}
				}
				break;
			}
		}

		if (!$match && strpos($sql, 'select') !== 0) {
			// We need to make sure all UPDATE, INSERT and DELETE queries are
			// mocked, otherwise we will be getting incorrect test results
			throw new \DatabaseException("No testing query spec was found");
		}
		
		$statement = $this->test->getMockBuilder(\Doctrine\DBAL\PDOStatement::class)
				->setMethods([
					'fetch',
					'rowCount',
				])
				->disableOriginalConstructor()
				->getMock();

		$statement->expects($this->test->any())
				->method('fetch')
				->will($this->test->returnCallback(function() use (&$results) {
							return array_shift($results);
						}));

		$statement->expects($this->test->any())
				->method('rowCount')
				->will($this->test->returnValue($row_count));

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
	 * @return string
	 */
	private function normalizeSql($query) {
		$query = trim($query);
		$query = str_replace('  ', ' ', $query);
		$query = strtolower($query);
		$query = preg_replace('~\\s+~', ' ', $query);
		return $query;
	}

}
