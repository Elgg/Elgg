<?php

namespace Elgg\Helpers\Database\Clauses;

use Elgg\Database\QueryBuilder;

/**
 * @see \Elgg\Database\Clauses\WhereClauseUnitTest
 */
class CallableWhere {
	
	/**
	 * Check if the callable can be an invokable class
	 *
	 * @param QueryBuilder $qb         QueryBuilder
	 * @param string       $main_alias table alias
	 *
	 * @return string
	 */
	public function __invoke(QueryBuilder $qb, $main_alias) {
		return $qb->expr()->eq('a', 'b');
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
		return $qb->expr()->eq('a', 'b');
	}
}
