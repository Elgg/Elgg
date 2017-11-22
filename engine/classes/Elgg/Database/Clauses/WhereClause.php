<?php

namespace Elgg\Database\Clauses;

use Closure;
use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Elgg\Database\QueryBuilder;

/**
 * Builds a clause from closure or composite expression
 */
class WhereClause implements Clause {

	/**
	 * @var Closure|CompositeExpression|null|string
	 */
	public $expr;

	/**
	 * Constructor
	 *
	 * @param CompositeExpression|Closure|string $expr Expression
	 */
	public function __construct($expr = null) {
		$this->expr = $expr;
	}

	/**
	 * {@inheritdoc}
	 */
	public function prepare(QueryBuilder $qb, $table_alias = null) {
		$where = $this->expr;

		if ($this->expr instanceof Closure) {
			$where = call_user_func($this->expr, $qb, $table_alias);
		}

		if ($where instanceof CompositeExpression || is_string($where)) {
			return $where;
		}
	}
}
