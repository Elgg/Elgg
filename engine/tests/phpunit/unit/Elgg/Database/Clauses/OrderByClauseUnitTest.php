<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\Helpers\Database\Clauses\CallableOrderBy;
use Elgg\UnitTestCase;

/**
 * @group QueryBuilder
 */
class OrderByClauseUnitTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable('entities', 'alias');
	}

	public function down() {

	}

	public function testBuildOrderByClauseFromString() {

		$this->qb->orderBy('alias.guid', 'desc');

		$query = new OrderByClause('alias.guid', 'desc');
		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildOrderByClauseFromClosure() {

		$this->qb->orderBy('alias.guid', 'asc');

		$query = new OrderByClause(function(QueryBuilder $qb) {
			return 'alias.guid';
		}, 'asc');

		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testBuildOrderByClauseFromInvokableClass() {

		$this->qb->orderBy('alias.guid', 'asc');

		$query = new OrderByClause(CallableOrderBy::class, 'asc');

		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testBuildOrderByClauseFromStaticClassFunction() {

		$this->qb->orderBy('alias.guid', 'asc');

		$query = new OrderByClause('\Elgg\Helpers\Database\Clauses\CallableOrderBy::callable', 'asc');

		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
}
