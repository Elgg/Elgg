<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\Helpers\Database\Clauses\CallableHaving;
use Elgg\UnitTestCase;

/**
 * @group QueryBuilder
 */
class HavingClauseUnitTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable('entities', 'alias');
	}

	public function down() {

	}

	public function testBuildHavingClauseFromString() {

		$this->qb->having('alias.guid = 25');

		$query = new HavingClause('alias.guid = 25');
		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildHavingClauseFromClosure() {

		$expr = $this->qb->compare('alias.guid', '=', 25, ELGG_VALUE_INTEGER);
		$this->qb->having($expr);

		$query = new HavingClause(function(QueryBuilder $qb)  {
			return $qb->compare('alias.guid', '=', 25, ELGG_VALUE_INTEGER);
		});

		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildHavingClauseFromCompositeExpression() {

		$expr = $this->qb->compare('alias.guid', '=', 25, ELGG_VALUE_INTEGER);
		$this->qb->having($expr);

		$qb = Select::fromTable('entities', 'alias');
		$query = new HavingClause($qb->compare('alias.guid', '=', 25, ELGG_VALUE_INTEGER));
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testBuildHavingClauseFromInvokableClass() {

		$expr = $this->qb->compare('alias.guid', '=', 25, ELGG_VALUE_INTEGER);
		$this->qb->having($expr);

		$qb = Select::fromTable('entities', 'alias');
		$query = new HavingClause(CallableHaving::class);
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testBuildHavingClauseFromStaticClassFunction() {

		$expr = $this->qb->compare('alias.guid', '=', 25, ELGG_VALUE_INTEGER);
		$this->qb->having($expr);

		$qb = Select::fromTable('entities', 'alias');
		$query = new HavingClause('\Elgg\Helpers\Database\Clauses\CallableHaving::callable');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
}
