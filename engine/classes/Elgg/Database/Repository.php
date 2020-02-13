<?php

namespace Elgg\Database;

use Elgg\Database\Clauses\GroupByClause;
use Elgg\Database\Clauses\HavingClause;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\Clauses\SelectClause;
use Elgg\Database\Clauses\WhereClause;
use Elgg\Exceptions\DataFormatException;

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
	public static function with(array $options = []) {
		$query = new static($options);

		return $query;
	}

	/**
	 * Build and execute a new query from an array of legacy options
	 *
	 * @param array $options Options
	 *
	 * @return \ElggData[]|int|mixed
	 */
	public static function find(array $options = []) {
		try {
			return static::with($options)->execute();
		} catch (DataFormatException $e) {
			return elgg_extract('count', $options) ? 0 : false;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function batch($limit = null, $offset = null, $callback = null) {

		$options = $this->options->getArrayCopy();

		$options['limit'] = (int) $limit;
		$options['offset'] = (int) $offset;
		$options['callback'] = $callback;
		unset($options['count'],
			$options['batch'],
			$options['batch_size'],
			$options['batch_inc_offset']
		);

		$batch_size = $this->options->batch_size;
		$batch_inc_offset = $this->options->batch_inc_offset;

		return new \ElggBatch([static::class, 'find'], $options, null, $batch_size, $batch_inc_offset);
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

	/**
	 * Extend query builder with select, group_by, having and order_by clauses from $options
	 *
	 * @param QueryBuilder $qb          Query builder
	 * @param string       $table_alias Table alias
	 *
	 * @return void
	 */
	public function expandInto(QueryBuilder $qb, $table_alias = null) {
		foreach ($this->options->selects as $select_clause) {
			$select_clause->prepare($qb, $table_alias);
		}

		foreach ($this->options->group_by as $group_by_clause) {
			$group_by_clause->prepare($qb, $table_alias);
		}

		foreach ($this->options->having as $having_clause) {
			$having_clause->prepare($qb, $table_alias);
		}

		if (!empty($this->options->order_by)) {
			foreach ($this->options->order_by as $order_by_clause) {
				$order_by_clause->prepare($qb, $table_alias);
			}
		}
	}
}
