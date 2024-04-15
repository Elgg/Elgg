<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\EntityTable;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\Helpers\Database\Clauses\CallableGroupBy;
use Elgg\UnitTestCase;

class GroupByClauseUnitTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$this->qb->select('*');
	}

	public function testBuildGroupByClauseFromString() {
		$this->qb->groupBy("{$this->qb->getTableAlias()}.guid");

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->select('*');
		
		$query = new GroupByClause("{$qb->getTableAlias()}.guid");
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildGroupByClauseFromClosure() {
		$this->qb->groupBy("{$this->qb->getTableAlias()}.guid");

		$query = new GroupByClause(function(QueryBuilder $qb, $main_alias) {
			return "{$main_alias}.guid";
		});

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->select('*');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testBuildGroupByClauseFromInvokableClass() {
		$this->qb->groupBy("{$this->qb->getTableAlias()}.guid");

		$query = new GroupByClause(CallableGroupBy::class);

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->select('*');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testBuildGroupByClauseFromStaticClassFunction() {
		$this->qb->groupBy("{$this->qb->getTableAlias()}.guid");

		$query = new GroupByClause('\Elgg\Helpers\Database\Clauses\CallableGroupBy::callable');

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->select('*');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
}
