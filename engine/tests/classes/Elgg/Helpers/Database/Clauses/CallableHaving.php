<?php

namespace Elgg\Helpers\Database\Clauses;

use Elgg\Database\QueryBuilder;

/**
 * @see \Elgg\Database\Clauses\HavingClauseUnitTest
 */
class CallableHaving {
	
	/**
	 * Check if the callable can be an invokable class
	 *
	 * @param QueryBuilder $qb         QueryBuilder
	 * @param string       $main_alias table alias
	 *
	 * @return string
	 */
	public function __invoke(QueryBuilder $qb, $main_alias) {
		return $qb->compare("{$main_alias}.guid", '=', 25, ELGG_VALUE_INTEGER);
	}
	
	/**
	 * Check if the callable can be a static class function
	 *
	 * @param QueryBuilder $qb         QueryBuilder
	 * @param string       $main_alias table alias
	 *
	 * @return string
	 */
	public static function callable(QueryBuilder $qb, $main_alias) {
		return $qb->compare("{$main_alias}.guid", '=', 25, ELGG_VALUE_INTEGER);
	}
}
