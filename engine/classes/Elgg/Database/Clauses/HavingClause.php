<?php

namespace Elgg\Database\Clauses;

use Closure;
use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Elgg\Database\QueryBuilder;

/**
 * Extends QueryBuilder with HAVING clauses
 */
class HavingClause extends Clause {

	/**
	 * @var Closure|CompositeExpression|string
	 */
	public $expr;

	/**
	 * Constructor
	 *
	 * @param CompositeExpression|Closure|string $expr Expression
	 */
	public function __construct($expr) {
		$this->expr = $expr;
	}

	/**
	 * {@inheritdoc}
	 */
	public function prepare(QueryBuilder $qb, $table_alias = null) {
		$having = $this->expr;

		if ($this->isCallable($having)) {
			$having = $this->call($having, $qb, $table_alias);
		}

		if ($having instanceof CompositeExpression || is_string($having)) {
			$qb->andHaving($having);
		}
	}
}
