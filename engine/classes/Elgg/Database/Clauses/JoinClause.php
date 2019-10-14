<?php

namespace Elgg\Database\Clauses;

use Closure;
use Doctrine\DBAL\Query\Expression\CompositeExpression;
use Elgg\Database\QueryBuilder;

/**
 * Extends QueryBuilder with JOIN clauses
 */
class JoinClause extends Clause {

	/**
	 * @var string
	 */
	public $joined_table;

	/**
	 * @var string
	 */
	public $joined_alias;

	/**
	 * @var CompositeExpression|Closure|string
	 */
	public $condition;

	/**
	 * @var string
	 */
	public $join_type;

	/**
	 * Constructor
	 *
	 * @param string                             $joined_table Table to join
	 * @param string                             $joined_alias Alias of the joined table
	 * @param CompositeExpression|Closure|string $condition    On expression
	 * @param string                             $join_type    Join type INNER|LEFT|RIGHT
	 */
	public function __construct($joined_table, $joined_alias = null, $condition = null, $join_type = 'inner') {
		$this->joined_table = $joined_table;
		$this->joined_alias = $joined_alias;
		$this->condition = $condition;
		$this->join_type = $join_type;
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

		switch (strtolower($this->join_type)) {
			case 'left' :
				$qb->leftJoin($table_alias, $this->joined_table, $joined_alias, $condition);
				break;

			case 'right' :
				$qb->rightJoin($table_alias, $this->joined_table, $joined_alias, $condition);
				break;

			default:
				$qb->join($table_alias, $this->joined_table, $joined_alias, $condition);
				break;
		}

		return $joined_alias;
	}
}
