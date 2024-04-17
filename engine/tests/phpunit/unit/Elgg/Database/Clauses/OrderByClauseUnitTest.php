<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\EntityTable;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\Helpers\Database\Clauses\CallableOrderBy;
use Elgg\UnitTestCase;

class OrderByClauseUnitTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$this->qb->select('*');
	}

	public function testBuildOrderByClauseFromString() {
		$this->qb->orderBy("{$this->qb->getTableAlias()}.guid", 'desc');

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->select('*');
		$query = new OrderByClause("{$qb->getTableAlias()}.guid", 'desc');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildOrderByClauseFromClosure() {
		$this->qb->orderBy("{$this->qb->getTableAlias()}.guid", 'asc');

		$query = new OrderByClause(function(QueryBuilder $qb, $main_alias) {
			return "{$main_alias}.guid";
		}, 'asc');

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->select('*');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testBuildOrderByClauseFromInvokableClass() {
		$this->qb->orderBy("{$this->qb->getTableAlias()}.guid", 'asc');

		$query = new OrderByClause(CallableOrderBy::class, 'asc');

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->select('*');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testBuildOrderByClauseFromStaticClassFunction() {
		$this->qb->orderBy("{$this->qb->getTableAlias()}.guid", 'asc');

		$query = new OrderByClause('\Elgg\Helpers\Database\Clauses\CallableOrderBy::callable', 'asc');

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->select('*');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
}
