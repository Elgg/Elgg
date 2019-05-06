<?php

namespace Elgg\Database;

use Closure;
use ElggBatch;

/**
 * This interface defines methods for building fluent interactions with a database repository
 */
interface QueryExecuting {

	/**
	 * Count rows
	 * @return int
	 */
	public function count();

	/**
	 * Apply numeric calculation to a column
	 *
	 * @param string $function      Calculation, e.g. max, min, avg
	 * @param string $property      Property name
	 * @param string $property_type Property type
	 *
	 * @return int|float
	 */
	public function calculate($function, $property, $property_type = null);

	/**
	 * Fetch rows
	 *
	 * @param int            $limit    Number of rows to fetch
	 * @param int            $offset   Index of the first row
	 * @param callable|false $callback Callback function to run database rows through
	 *
	 * @return \ElggData[]|false
	 */
	public function get($limit = null, $offset = null, $callback = null);

	/**
	 * Fetch rows as an ElggBatch
	 *
	 * @param int            $limit    Number of rows to fetch
	 * @param int            $offset   Index of the first row
	 * @param callable|false $callback Callback function to run database rows through
	 *
	 * @return ElggBatch
	 */
	public function batch($limit = null, $offset = null, $callback = null);

	/**
	 * Apply correct execution method based on calculation, count or other criteria
	 * @return mixed
	 */
	public function execute();

	/**
	 * Filter query prior to execution
	 * Callback function will receive QueryBuilder as the first argument and table alias as a second
	 * Callback function can either mutate the instance of the QueryBuilder or return a composition expression
	 * that will be appended to AND where statements
	 *
	 * @param Closure $closure Filter
	 *
	 * @return static
	 */
	public function filter(Closure $closure);

	/**
	 * Add SELECT
	 *
	 * @param mixed $expression Select
	 *
	 * @return static
	 */
	public function select($expression);

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
	public function join($joined_table, $joined_alias = null, $x = null, $comparison = null, $y = null, $type = null, $case_sensitive = null);

	/**
	 * Add GROUP BY
	 *
	 * @param string $expression Group by
	 *
	 * @return static
	 */
	public function groupBy($expression);

	/**
	 * Add HAVING
	 *
	 * @param string $expression Having
	 *
	 * @return static
	 */
	public function having($expression);

	/**
	 * Add ORDER BY
	 *
	 * @param string $expression Column/calculation
	 * @param string $direction  Direction
	 *
	 * @return static
	 */
	public function orderBy($expression, $direction);

}
