<?php

namespace Elgg\Database\Clauses;

use Closure;
use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Elgg\Database\QueryBuilder;

/**
 * Extends QueryBuilder with ORDER BY clauses
 */
class OrderByClause extends Clause {

	/**
	 * @var Closure|CompositeExpression|null|string
	 */
	public $expr;

	/**
	 * @var null|string
	 */
	public $direction;

	/**
	 * Constructor
	 *
	 * @param CompositeExpression|Closure|string $expr      Expression
	 * @param string                             $direction Direction
	 */
	public function __construct($expr = null, $direction = null) {
		$this->expr = $expr;
		$this->direction = $direction;
	}

	/**
	 * {@inheritdoc}
	 */
	public function prepare(QueryBuilder $qb, $table_alias = null) {
		$order_by = $this->expr;

		if ($this->isCallable($order_by)) {
			$order_by = $this->call($this->expr, $qb, $table_alias);
		}

		if ($order_by instanceof CompositeExpression || is_string($order_by)) {
			$qb->addOrderBy($order_by, $this->direction);
		}
	}
}
