<?php

namespace Elgg\Database\Clauses;

use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Elgg\Database\QueryBuilder;

/**
 * Extends QueryBuilder with JOIN clauses
 */
class JoinClause extends Clause {

	/**
	 * Constructor
	 *
	 * @param string                              $joined_table Table to join
	 * @param string                              $joined_alias Alias of the joined table
	 * @param CompositeExpression|\Closure|string $condition    On expression
	 * @param string                              $join_type    Join type INNER|LEFT|RIGHT
	 */
	public function __construct(public $joined_table, public $joined_alias = null, public $condition = null, public $join_type = 'inner') {
	}

	/**
	 * {@inheritdoc}
	 */
	public function prepare(QueryBuilder $qb, $table_alias = null) {
		$joined_alias = $this->joined_alias;
		if (!isset($joined_alias)) {
			$joined_alias = $qb->getNextJoinAlias();
		}

		$condition = $this->condition;
		if ($this->isCallable($condition)) {
			$condition = $this->callJoin($condition, $qb, $joined_alias, $table_alias);
		} else if ($condition === null) {
			$condition = true;
		}

		switch (strtolower($this->join_type ?? '')) {
			case 'left':
				$qb->leftJoin($table_alias, $this->joined_table, $joined_alias, $condition);
				break;

			case 'right':
				$qb->rightJoin($table_alias, $this->joined_table, $joined_alias, $condition);
				break;

			default:
				$qb->join($table_alias, $this->joined_table, $joined_alias, $condition);
				break;
		}

		return $joined_alias;
	}
}
