<?php

use Elgg\Database\QueryBuilder;

class CallableSelect {
	
	/**
	 * Check if the callable can be an invokable class
	 *
	 * @param QueryBuilder $qb         QueryBuilder
	 * @param string       $main_alias table alias
	 *
	 * @return string
	 */
	public function __invoke(QueryBuilder $qb, $main_alias) {
		return "{$main_alias}.guid AS g";
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
		return "{$main_alias}.guid AS g";
	}
}
