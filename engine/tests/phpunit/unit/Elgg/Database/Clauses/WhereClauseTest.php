<?php

namespace Elgg\Integration;

use Elgg\Database\Clauses\WhereClause;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\Project\Paths;
use Elgg\UnitTestCase;

require_once Paths::elgg() . 'engine/tests/test_files/database/clauses/CallableWhere.php';

/**
 * @group QueryBuilder
 * @group QueryBuilderWhere
 */
class WhereClauseTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable('entities', 'alias');
	}

	public function down() {

	}

	public function testBuildWhereClauseFromString() {

		$expected = "alias.guid = 25";

		$query = new WhereClause('alias.guid = 25');

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildWhereClauseFromClosure() {

		$expected = $this->qb->compare('alias.guid', '=', 25, ELGG_VALUE_INTEGER);

		$closure = function (QueryBuilder $qb) {
			return $qb->compare('alias.guid', '=', 25, ELGG_VALUE_INTEGER);
		};

		$query = new WhereClause($closure);

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildWhereClauseFromCompositeExpression() {

		$expr = $this->qb->expr()->eq('a', 'b');

		$expected = $expr;

		$query = new WhereClause($expr);

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testBuildWhereClauseFromInvokableClass() {

		$expr = $this->qb->expr()->eq('a', 'b');

		$expected = $expr;

		$query = new WhereClause(\CallableWhere::class);

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testBuildWhereClauseFromStaticClassFunction() {

		$expr = $this->qb->expr()->eq('a', 'b');

		$expected = $expr;

		$query = new WhereClause('\CallableWhere::callable');

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());

	}
}
