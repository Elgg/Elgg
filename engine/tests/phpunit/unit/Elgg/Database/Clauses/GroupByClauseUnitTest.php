<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\Helpers\Database\Clauses\CallableGroupBy;
use Elgg\UnitTestCase;

/**
 * @group QueryBuilder
 */
class GroupByClauseUnitTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable('entities', 'alias');
	}

	public function down() {

	}

	public function testBuildGroupByClauseFromString() {

		$this->qb->groupBy('alias.guid');

		$query = new GroupByClause('alias.guid');
		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildGroupByClauseFromClosure() {

		$this->qb->groupBy('alias.guid');

		$query = new GroupByClause(function(QueryBuilder $qb) {
			return 'alias.guid';
		});

		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testBuildGroupByClauseFromInvokableClass() {

		$this->qb->groupBy('alias.guid');

		$query = new GroupByClause(CallableGroupBy::class);

		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testBuildGroupByClauseFromStaticClassFunction() {

		$this->qb->groupBy('alias.guid');

		$query = new GroupByClause('\Elgg\Helpers\Database\Clauses\CallableGroupBy::callable');

		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
}
