<?php

namespace Elgg\Database\Clauses;

use Closure;
use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Elgg\Database\QueryBuilder;

/**
 * Extends QueryBuilder with GROUP BY statements
 */
class GroupByClause implements Clause {

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
		$group_by = $this->expr;

		if ($this->expr instanceof Closure) {
			$group_by = call_user_func($this->expr, $qb, $table_alias);
		}

		if ($group_by instanceof CompositeExpression || is_string($group_by)) {
			$qb->addGroupBy($group_by);
		}
	}
}
