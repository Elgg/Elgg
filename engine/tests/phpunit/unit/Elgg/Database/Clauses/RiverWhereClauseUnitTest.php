<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\UnitTestCase;

/**
 * @group QueryBuilder
 * @group QueryBuilderWhere
 * @group River
 */
class RiverWhereClauseUnitTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable('river', 'alias');
	}

	public function down() {

	}

	public function testBuildEmptyQuery() {

		$expected = null;

		$query = new RiverWhereClause();

		$qb = Select::fromTable('river', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromId() {

		$parts = [];
		$parts[] = $this->qb->expr()->eq('alias.id', ':qb1');
		$this->qb->param(1, ELGG_VALUE_INTEGER);
		$expected = $this->qb->merge($parts);

		$query = new RiverWhereClause();
		$query->ids = 1;

		$qb = Select::fromTable('river', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromAnnotationId() {

		$parts = [];
		$parts[] = $this->qb->expr()->eq('alias.annotation_id', ':qb1');
		$this->qb->param(1, ELGG_VALUE_INTEGER);
		$expected = $this->qb->merge($parts);

		$query = new RiverWhereClause();
		$query->annotation_ids = 1;

		$qb = Select::fromTable('river', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromView() {

		$parts = [];
		$parts[] = $this->qb->expr()->in('alias.view', ':qb1');
		$this->qb->param(['view1', 'dir/view2'], ELGG_VALUE_STRING);
		$expected = $this->qb->merge($parts);

		$query = new RiverWhereClause();
		$query->views = ['view1', 'dir/view2'];

		$qb = Select::fromTable('river', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromActionType() {

		$parts = [];
		$parts[] = $this->qb->expr()->in('alias.action_type', ':qb1');
		$this->qb->param(['foo1', 'foo2'], ELGG_VALUE_STRING);
		$expected = $this->qb->merge($parts);

		$query = new RiverWhereClause();
		$query->action_types = ['foo1', 'foo2'];

		$qb = Select::fromTable('river', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromGuids() {

		$parts = [];
		$parts[] = $this->qb->expr()->in('alias.subject_guid', ':qb1');
		$parts[] = $this->qb->expr()->in('alias.object_guid', ':qb2');
		$parts[] = $this->qb->expr()->in('alias.target_guid', ':qb3');
		$this->qb->param([1, 2, 3], ELGG_VALUE_INTEGER);
		$this->qb->param([4, 5, 6], ELGG_VALUE_INTEGER);
		$this->qb->param([7, 8, 9], ELGG_VALUE_INTEGER);
		$expected = $this->qb->merge($parts);

		$query = new RiverWhereClause();
		$query->subject_guids = [1, 2, 3];
		$query->object_guids = [4, 5, 6];
		$query->target_guids = [7, 8, 9];

		$qb = Select::fromTable('river', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromTimeCreated() {

		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime())->modify('+1 day');

		$parts = [];

		$time_parts = [];
		$time_parts[] = $this->qb->expr()->gte('alias.posted', ':qb1');
		$time_parts[] = $this->qb->expr()->lte('alias.posted', ':qb2');
		$this->qb->param($after->getTimestamp(), ELGG_VALUE_INTEGER);
		$this->qb->param($before->getTimestamp(), ELGG_VALUE_INTEGER);
		$parts[] = $this->qb->merge($time_parts);

		$expected = $this->qb->merge($parts);

		$query = new RiverWhereClause();
		$query->created_after = $after;
		$query->created_before = $before;

		$qb = Select::fromTable('river', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());

	}
}
