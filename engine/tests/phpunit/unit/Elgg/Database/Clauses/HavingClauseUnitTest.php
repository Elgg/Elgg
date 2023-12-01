<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\EntityTable;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\Helpers\Database\Clauses\CallableHaving;
use Elgg\UnitTestCase;

class HavingClauseUnitTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
	}

	public function testBuildHavingClauseFromString() {
		$this->qb->having("{$this->qb->getTableAlias()}.guid = 25");

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$query = new HavingClause("{$qb->getTableAlias()}.guid = 25");
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildHavingClauseFromClosure() {
		$expr = $this->qb->compare("{$this->qb->getTableAlias()}.guid", '=', 25, ELGG_VALUE_INTEGER);
		$this->qb->having($expr);

		$query = new HavingClause(function(QueryBuilder $qb, $main_alias)  {
			return $qb->compare("{$main_alias}.guid", '=', 25, ELGG_VALUE_INTEGER);
		});

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildHavingClauseFromCompositeExpression() {
		$expr = $this->qb->compare("{$this->qb->getTableAlias()}.guid", '=', 25, ELGG_VALUE_INTEGER);
		$this->qb->having($expr);

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$query = new HavingClause($qb->compare("{$qb->getTableAlias()}.guid", '=', 25, ELGG_VALUE_INTEGER));
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testBuildHavingClauseFromInvokableClass() {
		$expr = $this->qb->compare("{$this->qb->getTableAlias()}.guid", '=', 25, ELGG_VALUE_INTEGER);
		$this->qb->having($expr);

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$query = new HavingClause(CallableHaving::class);
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testBuildHavingClauseFromStaticClassFunction() {
		$expr = $this->qb->compare("{$this->qb->getTableAlias()}.guid", '=', 25, ELGG_VALUE_INTEGER);
		$this->qb->having($expr);

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$query = new HavingClause('\Elgg\Helpers\Database\Clauses\CallableHaving::callable');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
}
