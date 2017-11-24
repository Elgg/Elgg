<?php

namespace Elgg\Integration;

use Elgg\Database\Clauses\GroupByClause;
use Elgg\Database\Clauses\SelectClause;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\UnitTestCase;

/**
 * @group QueryBuilder
 */
class SelectClauseTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable('entities', 'alias');
	}

	public function down() {

	}

	public function testBuildSelectClauseFromString() {

		$this->qb->select('alias.guid AS g');

		$query = new SelectClause('alias.guid AS g');
		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildSelectClauseFromClosure() {

		$this->qb->select('alias.guid AS g');

		$query = new SelectClause(function(QueryBuilder $qb) {
			return 'alias.guid AS g';
		});

		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
}
