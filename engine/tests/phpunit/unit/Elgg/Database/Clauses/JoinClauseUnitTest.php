<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\Helpers\Database\Clauses\CallableJoin;
use Elgg\UnitTestCase;

/**
 * @group QueryBuilder
 */
class JoinClauseUnitTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable('entities', 'alias');
	}

	public function down() {

	}

	public function testBuildJoinClauseWithoutCondition() {

		$this->qb->join('alias', 'joined_table', 'joined_alias', true);

		$join = new JoinClause('joined_table', 'joined_alias');
		$expected = Select::fromTable('entities', 'alias');
		$expected->addClause($join);

		$this->assertEquals($this->qb->getSQL(), $expected->getSQL());
		$this->assertEquals($this->qb->getParameters(), $expected->getParameters());
	}

	public function testBuildJoinClauseWithStringCondition() {

		$this->qb->join('alias', 'joined_table', 'joined_alias', 'joined_alias.x = alias.x');

		$join = new JoinClause('joined_table', 'joined_alias', 'joined_alias.x = alias.x');
		$expected = Select::fromTable('entities', 'alias');
		$expected->addClause($join);

		$this->assertEquals($this->qb->getSQL(), $expected->getSQL());
		$this->assertEquals($this->qb->getParameters(), $expected->getParameters());
	}


	public function testBuildJoinClauseWithClosureCondition() {

		$this->qb->join('alias', 'joined_table', 'joined_alias', 'joined_alias.x = alias.x');

		$condition = function(QueryBuilder $qb, $joined_alias, $main_alias) {
			return $qb->compare("$joined_alias.x", '=', "$main_alias.x");
		};
		$join = new JoinClause('joined_table', 'joined_alias', $condition);
		$expected = Select::fromTable('entities', 'alias');
		$expected->addClause($join);

		$this->assertEquals($this->qb->getSQL(), $expected->getSQL());
		$this->assertEquals($this->qb->getParameters(), $expected->getParameters());
	}

	public function testBuildJoinClauseWithCompositeExpressionCondition() {

		$this->qb->join('alias', 'joined_table', 'joined_alias', 'joined_alias.x = alias.x');

		$expected = Select::fromTable('entities', 'alias');
		$condition = $expected->compare("joined_alias.x", '=', "alias.x");
		$join = new JoinClause('joined_table', 'joined_alias', $condition);
		$expected->addClause($join);

		$this->assertEquals($this->qb->getSQL(), $expected->getSQL());
		$this->assertEquals($this->qb->getParameters(), $expected->getParameters());
	}
	
	public function testBuildJoinClauseWithInvokableClassCondition() {

		$this->qb->join('alias', 'joined_table', 'joined_alias', 'joined_alias.x = alias.x');

		$expected = Select::fromTable('entities', 'alias');
		$join = new JoinClause('joined_table', 'joined_alias', CallableJoin::class);
		$expected->addClause($join);

		$this->assertEquals($this->qb->getSQL(), $expected->getSQL());
		$this->assertEquals($this->qb->getParameters(), $expected->getParameters());
	}
	
	public function testBuildJoinClauseWithStaticClassFunctionCondition() {

		$this->qb->join('alias', 'joined_table', 'joined_alias', 'joined_alias.x = alias.x');

		$expected = Select::fromTable('entities', 'alias');
		$join = new JoinClause('joined_table', 'joined_alias', '\Elgg\Helpers\Database\Clauses\CallableJoin::callable');
		$expected->addClause($join);

		$this->assertEquals($this->qb->getSQL(), $expected->getSQL());
		$this->assertEquals($this->qb->getParameters(), $expected->getParameters());
	}

	public function testBuildInnerJoin() {

		$this->qb->innerJoin('alias', 'joined_table', 'joined_alias', true);

		$join = new JoinClause('joined_table', 'joined_alias', null, 'INNER');
		$expected = Select::fromTable('entities', 'alias');
		$expected->addClause($join);

		$this->assertEquals($this->qb->getSQL(), $expected->getSQL());
		$this->assertEquals($this->qb->getParameters(), $expected->getParameters());
	}

	public function testBuildLeftJoin() {

		$this->qb->leftJoin('alias', 'joined_table', 'joined_alias', true);

		$join = new JoinClause('joined_table', 'joined_alias', null, 'left');
		$expected = Select::fromTable('entities', 'alias');
		$expected->addClause($join);

		$this->assertEquals($this->qb->getSQL(), $expected->getSQL());
		$this->assertEquals($this->qb->getParameters(), $expected->getParameters());
	}

	public function testBuildRightJoin() {

		$this->qb->rightJoin('alias', 'joined_table', 'joined_alias', true);

		$join = new JoinClause('joined_table', 'joined_alias', null, 'right');
		$expected = Select::fromTable('entities', 'alias');
		$expected->addClause($join);

		$this->assertEquals($this->qb->getSQL(), $expected->getSQL());
		$this->assertEquals($this->qb->getParameters(), $expected->getParameters());
	}
}
