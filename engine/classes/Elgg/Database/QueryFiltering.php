<?php

namespace Elgg\Database;

use Elgg\Database\Clauses\GroupByClause;
use Elgg\Database\Clauses\HavingClause;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\Clauses\SelectClause;
use Elgg\Database\Clauses\WhereClause;

/**
 * This interface defines methods for filtering/extending database queries
 */
interface QueryFiltering {

	/**
	 * Return DISTINCT rows
	 *
	 * @param bool $distinct Distinct
	 *
	 * @return static
	 */
	public function distinct($distinct = true);

	/**
	 * Add where statement
	 *
	 * @param WhereClause $clause Clause
	 *
	 * @return static
	 */
	public function where(WhereClause $clause);

	/**
	 * Add a select statement
	 *
	 * @param SelectClause $clause Clause
	 *
	 * @return $this
	 */
	public function select(SelectClause $clause);

	/**
	 * Add a join callback
	 *
	 * @param JoinClause $clause Clause
	 *
	 * @return static
	 */
	public function join(JoinClause $clause);

	/**
	 * Add group by statement
	 *
	 * @param GroupByClause $clause Clause
	 *
	 * @return static
	 */
	public function groupBy(GroupByClause $clause);

	/**
	 * Add having statement
	 *
	 * @param HavingClause $clause Clause
	 *
	 * @return static
	 */
	public function having(HavingClause $clause);

	/**
	 * Add order by statement
	 *
	 * @param OrderByClause $clause Clause
	 *
	 * @return static
	 */
	public function orderBy(OrderByClause $clause);
}
