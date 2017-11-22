<?php

namespace Elgg\Integration;

use Elgg\Database\Clauses\AccessWhereClause;
use Elgg\Database\Clauses\EntityWhereClause;
use Elgg\Database\Clauses\RelationshipWhereClause;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\UnitTestCase;

/**
 * @group QueryBuilder
 * @group QueryBuilderWhere
 */
class RelationshipWhereClauseTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable('entities', 'alias');
	}

	public function down() {

	}

	public function testBuildEmptyQuery() {

		$expected = null;

		$query = new RelationshipWhereClause();

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromId() {

		$parts = [];
		$parts[] = $this->qb->expr()->eq('alias.id', ':qb1');
		$this->qb->param(1, ELGG_VALUE_INTEGER);
		$expected = $this->qb->merge($parts);

		$query = new RelationshipWhereClause();
		$query->ids = 1;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromName() {

		$parts = [];
		$parts[] = $this->qb->expr()->eq('alias.relationship', ':qb1');
		$this->qb->param('friend', ELGG_VALUE_STRING);
		$expected = $this->qb->merge($parts);

		$query = new RelationshipWhereClause();
		$query->names = 'friend';

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromNames() {

		$parts = [];
		$parts[] = $this->qb->expr()->in('alias.relationship', ':qb1');
		$this->qb->param(['friend', 'member'], ELGG_VALUE_STRING);
		$expected = $this->qb->merge($parts);

		$query = new RelationshipWhereClause();
		$query->names = ['friend', 'member'];

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromGuids() {

		$parts = [];
		$parts[] = $this->qb->expr()->in('alias.guid_one', ':qb1');
		$parts[] = $this->qb->expr()->in('alias.guid_two', ':qb2');
		$this->qb->param([2, 3, 4], ELGG_VALUE_INTEGER);
		$this->qb->param([5, 6, 7], ELGG_VALUE_INTEGER);
		$expected = $this->qb->merge($parts);

		$query = new RelationshipWhereClause();
		$query->subject_guids = [2, 3, 4];
		$query->object_guids = [5, 6, 7];

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromTimeCreated() {

		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime())->modify('+1 day');

		$parts = [];

		$time_parts = [];
		$time_parts[] = $this->qb->expr()->gte('alias.time_created', ':qb1');
		$time_parts[] = $this->qb->expr()->lte('alias.time_created', ':qb2');
		$this->qb->param($after->getTimestamp(), ELGG_VALUE_INTEGER);
		$this->qb->param($before->getTimestamp(), ELGG_VALUE_INTEGER);
		$parts[] = $this->qb->merge($time_parts);

		$expected = $this->qb->merge($parts);

		$query = new RelationshipWhereClause();
		$query->created_after = $after;
		$query->created_before = $before;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());

	}

}

