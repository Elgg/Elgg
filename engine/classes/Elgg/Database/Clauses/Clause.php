<?php

namespace Elgg\Database\Clauses;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Elgg\Database\QueryBuilder;

/**
 * Interface that allows resolving statements and/or extending query builder
 */
interface Clause {

	/**
	 * Build an expression and/or apply it to an instance of query builder
	 *
	 * @param QueryBuilder $qb          Query builder
	 * @param null         $table_alias Table alias
	 *
	 * @return CompositeExpression|null|string
	 */
	public function prepare(QueryBuilder $qb, $table_alias = null);
}
