<?php

namespace Elgg\Database;

use Elgg\Database\Clauses\GroupByClause;
use Elgg\Database\Clauses\HavingClause;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\Clauses\SelectClause;
use Elgg\Database\Clauses\WhereClause;

/**
 * Abstract methods for interfacing with the database
 */
abstract class Repository implements QueryExecuting {

	/**
	 * @var QueryOptions
	 */
	protected $options;

	/**
	 * Constructor
	 *
	 * @param array $options ege* options
	 */
	public function __construct(array $options = []) {
		$this->options = new QueryOptions($options, \ArrayObject::ARRAY_AS_PROPS);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __get($name) {
		if (!isset($this->options->$name)) {
			return;
		}

		$val = &$this->options->$name;

		return $val;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __set($name, $value) {
		$this->options->$name = $value;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __unset($name) {
		unset($this->options->$name);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __isset($name) {
		return isset($this->options->$name);
	}

	/**
	 * Constructs a new
	 *
	 * @param array $options ege* options
	 *
	 * @return static
	 */
	public static function with(array $options = null) {
		$query = new static($options);

		return $query;
	}

	/**
	 * {@inheritdoc}
	 */
	public function filter(\Closure $closure) {
		$this->options->where(new WhereClause($closure));

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function select($expression) {
		$this->options->select(new SelectClause($expression));

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function join($joined_table, $joined_alias = null, $x = null, $comparison = null, $y = null, $type = null, $case_sensitive = null) {
		$join = new JoinClause($joined_table, $joined_alias, function (QueryBuilder $qb, $joined_alias) use ($x, $comparison, $y, $type, $case_sensitive) {
			return $qb->compare("$joined_alias.$x", $comparison, $y, $type, $case_sensitive);
		});
		$this->options->join($join);

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function groupBy($expression) {
		$this->options->groupBy(new GroupByClause($expression));

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function having($expression) {
		$this->options->having(new HavingClause($expression));

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function orderBy($expression, $direction) {
		$this->options->orderBy(new OrderByClause($expression, $direction));

		return $this;
	}
}
