<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\EntityTable;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\Helpers\Database\Clauses\CallableWhere;
use Elgg\UnitTestCase;

class WhereClauseUnitTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
	}

	public function testBuildWhereClauseFromString() {

		$expected = "{$this->qb->getTableAlias()}.guid = 25";

		$query = new WhereClause("{$this->qb->getTableAlias()}.guid = 25");

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, $qb->getTableAlias());

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildWhereClauseFromClosure() {

		$expected = $this->qb->compare("{$this->qb->getTableAlias()}.guid", '=', 25, ELGG_VALUE_INTEGER);

		$closure = function (QueryBuilder $qb) {
			return $qb->compare("{$this->qb->getTableAlias()}.guid", '=', 25, ELGG_VALUE_INTEGER);
		};

		$query = new WhereClause($closure);

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildWhereClauseFromCompositeExpression() {

		$expr = $this->qb->expr()->eq('a', 'b');

		$expected = $expr;

		$query = new WhereClause($expr);

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testBuildWhereClauseFromInvokableClass() {

		$expr = $this->qb->expr()->eq('a', 'b');

		$expected = $expr;

		$query = new WhereClause(CallableWhere::class);

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testBuildWhereClauseFromStaticClassFunction() {

		$expr = $this->qb->expr()->eq('a', 'b');

		$expected = $expr;

		$query = new WhereClause('\Elgg\Helpers\Database\Clauses\CallableWhere::callable');

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
}
