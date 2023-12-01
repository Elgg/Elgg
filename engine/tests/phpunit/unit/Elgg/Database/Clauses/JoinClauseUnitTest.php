<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\EntityTable;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\Helpers\Database\Clauses\CallableJoin;
use Elgg\UnitTestCase;

class JoinClauseUnitTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
	}

	public function testBuildJoinClauseWithoutCondition() {
		$this->qb->join($this->qb->getTableAlias(), 'joined_table', 'joined_alias', true);

		$join = new JoinClause('joined_table', 'joined_alias');
		$expected = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$expected->addClause($join);

		$this->assertEquals($this->qb->getSQL(), $expected->getSQL());
		$this->assertEquals($this->qb->getParameters(), $expected->getParameters());
	}

	public function testBuildJoinClauseWithStringCondition() {
		$this->qb->join($this->qb->getTableAlias(), 'joined_table', 'joined_alias', "joined_alias.x = {$this->qb->getTableAlias()}.x");

		$expected = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$join = new JoinClause('joined_table', 'joined_alias', "joined_alias.x = {$expected->getTableAlias()}.x");
		$expected->addClause($join);

		$this->assertEquals($this->qb->getSQL(), $expected->getSQL());
		$this->assertEquals($this->qb->getParameters(), $expected->getParameters());
	}


	public function testBuildJoinClauseWithClosureCondition() {
		$this->qb->join($this->qb->getTableAlias(), 'joined_table', 'joined_alias', "joined_alias.x = {$this->qb->getTableAlias()}.x");

		$condition = function(QueryBuilder $qb, $joined_alias, $main_alias) {
			return $qb->compare("{$joined_alias}.x", '=', "{$main_alias}.x");
		};
		$join = new JoinClause('joined_table', 'joined_alias', $condition);
		$expected = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$expected->addClause($join);

		$this->assertEquals($this->qb->getSQL(), $expected->getSQL());
		$this->assertEquals($this->qb->getParameters(), $expected->getParameters());
	}

	public function testBuildJoinClauseWithCompositeExpressionCondition() {
		$this->qb->join($this->qb->getTableAlias(), 'joined_table', 'joined_alias', "joined_alias.x = {$this->qb->getTableAlias()}.x");

		$expected = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$condition = $expected->compare('joined_alias.x', '=', "{$expected->getTableAlias()}.x");
		$join = new JoinClause('joined_table', 'joined_alias', $condition);
		$expected->addClause($join);

		$this->assertEquals($this->qb->getSQL(), $expected->getSQL());
		$this->assertEquals($this->qb->getParameters(), $expected->getParameters());
	}
	
	public function testBuildJoinClauseWithInvokableClassCondition() {
		$this->qb->join($this->qb->getTableAlias(), 'joined_table', 'joined_alias', "joined_alias.x = {$this->qb->getTableAlias()}.x");

		$expected = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$join = new JoinClause('joined_table', 'joined_alias', CallableJoin::class);
		$expected->addClause($join);

		$this->assertEquals($this->qb->getSQL(), $expected->getSQL());
		$this->assertEquals($this->qb->getParameters(), $expected->getParameters());
	}
	
	public function testBuildJoinClauseWithStaticClassFunctionCondition() {
		$this->qb->join($this->qb->getTableAlias(), 'joined_table', 'joined_alias', "joined_alias.x = {$this->qb->getTableAlias()}.x");

		$expected = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$join = new JoinClause('joined_table', 'joined_alias', '\Elgg\Helpers\Database\Clauses\CallableJoin::callable');
		$expected->addClause($join);

		$this->assertEquals($this->qb->getSQL(), $expected->getSQL());
		$this->assertEquals($this->qb->getParameters(), $expected->getParameters());
	}

	public function testBuildInnerJoin() {
		$this->qb->innerJoin($this->qb->getTableAlias(), 'joined_table', 'joined_alias', true);

		$join = new JoinClause('joined_table', 'joined_alias', null, 'INNER');
		$expected = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$expected->addClause($join);

		$this->assertEquals($this->qb->getSQL(), $expected->getSQL());
		$this->assertEquals($this->qb->getParameters(), $expected->getParameters());
	}

	public function testBuildLeftJoin() {
		$this->qb->leftJoin($this->qb->getTableAlias(), 'joined_table', 'joined_alias', true);

		$join = new JoinClause('joined_table', 'joined_alias', null, 'left');
		$expected = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$expected->addClause($join);

		$this->assertEquals($this->qb->getSQL(), $expected->getSQL());
		$this->assertEquals($this->qb->getParameters(), $expected->getParameters());
	}

	public function testBuildRightJoin() {
		$this->qb->rightJoin($this->qb->getTableAlias(), 'joined_table', 'joined_alias', true);

		$join = new JoinClause('joined_table', 'joined_alias', null, 'right');
		$expected = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$expected->addClause($join);

		$this->assertEquals($this->qb->getSQL(), $expected->getSQL());
		$this->assertEquals($this->qb->getParameters(), $expected->getParameters());
	}
}
