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
abstract class Repository {

	protected QueryOptions $options;

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
		return new static($options);
	}
	
	/**
	 * Count rows
	 *
	 * @return int
	 */
	abstract public function count();
	
	/**
	 * Apply numeric calculation to a column
	 *
	 * @param string $function      Calculation, e.g. max, min, avg
	 * @param string $property      Property name
	 * @param string $property_type Property type
	 *
	 * @return int|float
	 */
	abstract public function calculate($function, $property, $property_type = null);
	
	/**
	 * Fetch rows
	 *
	 * @param int            $limit    Number of rows to fetch
	 * @param int            $offset   Index of the first row
	 * @param callable|false $callback Callback function to run database rows through
	 *
	 * @return \ElggData[]|false
	 */
	abstract public function get($limit = null, $offset = null, $callback = null);

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
	 * Fetch rows as an ElggBatch
	 *
	 * @param int            $limit    Number of rows to fetch
	 * @param int            $offset   Index of the first row
	 * @param callable|false $callback Callback function to run database rows through
	 *
	 * @return \ElggBatch
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
	 * Apply correct execution method based on calculation, count or other criteria
	 *
	 * @return mixed
	 */
	abstract public function execute();

	/**
	 * Filter query prior to execution
	 * Callback function will receive QueryBuilder as the first argument and table alias as a second
	 * Callback function can either mutate the instance of the QueryBuilder or return a composition expression
	 * that will be appended to AND where statements
	 *
	 * @param \Closure $closure Filter
	 *
	 * @return static
	 */
	public function filter(\Closure $closure) {
		$this->options->where(new WhereClause($closure));

		return $this;
	}

	/**
	 * Add SELECT
	 *
	 * @param mixed $expression Select
	 *
	 * @return static
	 */
	public function select($expression) {
		$this->options->select(new SelectClause($expression));

		return $this;
	}

	/**
	 * Add JOIN clause
	 * Join a database table on an $x to $y comparison
	 *
	 * @param string $joined_table   Name of the table (with or without dbprefix)
	 * @param string $joined_alias   Alias of the joined table
	 *                               If not set, the alias will be assigned automatically
	 * @param string $x              Base column, e.g. 'n_table.entity_guid'
	 *                               This value is NOT a query parameter and will not be sanitized
	 * @param string $comparison     Comparison operator, e.g. '=', 'not like' etc
	 * @param mixed  $y              Comparison value(s)
	 * @param string $type           Type of the comparison value(s), e.g. ELGG_VALUE_STRING, ELGG_VALUE_INT
	 * @param bool   $case_sensitive Use case senstivie comparison for string values
	 *
	 * @return static
	 */
	public function join($joined_table, $joined_alias = null, $x = null, $comparison = null, $y = null, $type = null, $case_sensitive = null) {
		$join = new JoinClause($joined_table, $joined_alias, function (QueryBuilder $qb, $joined_alias) use ($x, $comparison, $y, $type, $case_sensitive) {
			return $qb->compare("$joined_alias.$x", $comparison, $y, $type, $case_sensitive);
		});
		$this->options->join($join);

		return $this;
	}

	/**
	 * Add GROUP BY
	 *
	 * @param string $expression Group by
	 *
	 * @return static
	 */
	public function groupBy($expression) {
		$this->options->groupBy(new GroupByClause($expression));

		return $this;
	}

	/**
	 * Add HAVING
	 *
	 * @param string $expression Having
	 *
	 * @return static
	 */
	public function having($expression) {
		$this->options->having(new HavingClause($expression));

		return $this;
	}

	/**
	 * Add ORDER BY
	 *
	 * @param string $expression Column/calculation
	 * @param string $direction  Direction
	 *
	 * @return static
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
