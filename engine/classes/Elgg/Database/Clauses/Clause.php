<?php

namespace Elgg\Database\Clauses;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Elgg\Database\QueryBuilder;
use Elgg\Traits\Loggable;

/**
 * Interface that allows resolving statements and/or extending query builder
 *
 * @internal
 */
abstract class Clause {

	use Loggable;
	
	/**
	 * Build an expression and/or apply it to an instance of query builder
	 *
	 * @param QueryBuilder $qb          Query builder
	 * @param string       $table_alias Table alias
	 *
	 * @return CompositeExpression|null|string
	 */
	abstract public function prepare(QueryBuilder $qb, $table_alias = null);
	
	/**
	 * Check if a clause expression is callable
	 *
	 * @param mixed $callback the clause callable expression
	 *
	 * @return bool
	 */
	protected function isCallable($callback) {
		return _elgg_services()->handlers->isCallable($callback);
	}
	
	/**
	 * Call the expression for the clause
	 *
	 * @param mixed        $callback    the clause callable expression
	 * @param QueryBuilder $qb          the current query builder
	 * @param string       $table_alias the main table alias
	 *
	 * @return false|mixed
	 */
	protected function call($callback, QueryBuilder $qb, $table_alias = null) {
		$service = _elgg_services()->handlers;
		
		$callable = $service->resolveCallable($callback);
		if (!is_callable($callable)) {
			$description = static::class . ' (QueryBuilder, table_alias)';
			$this->getLogger()->warning("Handler for {$description} is not callable: " . $service->describeCallable($callback));
			
			return false;
		}
		
		return call_user_func($callable, $qb, $table_alias);
	}
	
	/**
	 * Call the expression for a join clause
	 *
	 * @param mixed        $callback     the clause callable expression
	 * @param QueryBuilder $qb           the current query builder
	 * @param string       $joined_alias the joined table alias
	 * @param string       $table_alias  the main table alias
	 *
	 * @return false|mixed
	 */
	protected function callJoin($callback, QueryBuilder $qb, $joined_alias, $table_alias = null) {
		$service = _elgg_services()->handlers;
		
		$callable = $service->resolveCallable($callback);
		if (!is_callable($callable)) {
			$description = static::class . ' (QueryBuilder, joined_alias, table_alias)';
			$this->getLogger()->warning("Handler for {$description} is not callable: " . $service->describeCallable($callback));
			
			return false;
		}
		
		return call_user_func($callable, $qb, $joined_alias, $table_alias);
	}
}
