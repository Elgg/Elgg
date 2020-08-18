<?php

namespace Elgg\Helpers\Database\Clauses;

use Elgg\Database\QueryBuilder;

/**
 * @see \Elgg\Database\Clauses\JoinClauseUnitTest
 */
class CallableJoin {
	
	/**
	 * Check if the callable can be an invokable class
	 *
	 * @param QueryBuilder $qb           QueryBuilder
	 * @param string       $joined_alias table alias
	 * @param string       $main_alias   table alias
	 *
	 * @return string
	 */
	public function __invoke(QueryBuilder $qb, $joined_alias, $main_alias) {
		return $qb->compare("{$joined_alias}.x", '=', "{$main_alias}.x");
	}
	
	/**
	 * Check if the callable can be a static class function
	 *
	 * @param QueryBuilder $qb           QueryBuilder
	 * @param string       $joined_alias table alias
	 * @param string       $main_alias   table alias
	 *
	 * @return string
	 */
	public static function callable(QueryBuilder $qb, $joined_alias, $main_alias) {
		return $qb->compare("{$joined_alias}.x", '=', "{$main_alias}.x");
	}
}
