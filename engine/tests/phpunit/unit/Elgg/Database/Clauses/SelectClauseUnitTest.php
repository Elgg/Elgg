<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\EntityTable;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\Helpers\Database\Clauses\CallableSelect;
use Elgg\UnitTestCase;

class SelectClauseUnitTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
	}

	public function testBuildSelectClauseFromString() {
		$this->qb->select("{$this->qb->getTableAlias()}.guid AS g");

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$query = new SelectClause("{$qb->getTableAlias()}.guid AS g");
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildSelectClauseFromClosure() {
		$this->qb->select("{$this->qb->getTableAlias()}.guid AS g");

		$query = new SelectClause(function(QueryBuilder $qb, $main_alias) {
			return "{$main_alias}.guid AS g";
		});

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testBuildSelectClauseFromInvokableClass() {
		$this->qb->select("{$this->qb->getTableAlias()}.guid AS g");

		$query = new SelectClause(CallableSelect::class);

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testBuildSelectClauseFromStaticClassFunction() {
		$this->qb->select("{$this->qb->getTableAlias()}.guid AS g");

		$query = new SelectClause('\Elgg\Helpers\Database\Clauses\CallableSelect::callable');

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
}
