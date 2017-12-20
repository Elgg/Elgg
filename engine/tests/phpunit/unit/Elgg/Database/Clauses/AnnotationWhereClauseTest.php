<?php

namespace Elgg\Integration;

use Elgg\Database\Clauses\AccessWhereClause;
use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\UnitTestCase;

/**
 * @group QueryBuilder
 * @group QueryBuilderWhere
 */
class AnnotationWhereClauseTest extends UnitTestCase {

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

		$query = new AnnotationWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;

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

		$query = new AnnotationWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;
		$query->ids = 1;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromName() {

		$parts = [];
		$parts[] = $this->qb->expr()->in('alias.name', ':qb1');
		$this->qb->param(['foo1', 'foo2'], ELGG_VALUE_STRING);
		$expected = $this->qb->merge($parts);

		$query = new AnnotationWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;
		$query->names = ['foo1', 'foo2'];

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromValue() {

		$parts = [];
		$parts[] = $this->qb->expr()->in('alias.value', ':qb1');
		$this->qb->param(['foo1', 'foo2'], ELGG_VALUE_STRING);
		$expected = $this->qb->merge($parts);

		$query = new AnnotationWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;
		$query->values = ['foo1', 'foo2'];
		$query->value_type = ELGG_VALUE_STRING;
		$query->case_sensitive = false;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromNameValueWithComparison() {

		$parts = [];
		$parts[] = $this->qb->expr()->in('alias.name', ':qb1');
		$parts[] = $this->qb->expr()->like('alias.value', 'BINARY :qb2');
		$this->qb->param(['foo1', 'foo2'], ELGG_VALUE_STRING);
		$this->qb->param('%bar%', ELGG_VALUE_STRING);
		$expected = $this->qb->merge($parts);

		$query = new AnnotationWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;
		$query->names = ['foo1', 'foo2'];
		$query->values = '%bar%';
		$query->value_type = ELGG_VALUE_STRING;
		$query->comparison = 'like';

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromGuid() {

		$parts = [];
		$parts[] = $this->qb->expr()->eq('alias.entity_guid', ':qb1');
		$this->qb->param(1, ELGG_VALUE_INTEGER);
		$expected = $this->qb->merge($parts);

		$query = new AnnotationWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;
		$query->entity_guids = 1;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromOwnerGuid() {

		$parts = [];
		$parts[] = $this->qb->expr()->in('alias.owner_guid', ':qb1');
		$this->qb->param([2, 3], ELGG_VALUE_INTEGER);
		$expected = $this->qb->merge($parts);

		$query = new AnnotationWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;
		$query->owner_guids = [2, 3];

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

		$query = new AnnotationWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;
		$query->created_after = $after;
		$query->created_before = $before;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());

	}

	public function testBuildQueryFromEnabled() {

		$parts = [];
		$parts[] = $this->qb->expr()->eq('alias.enabled', ':qb1');
		$this->qb->param('no', ELGG_VALUE_STRING);
		$expected = $this->qb->merge($parts);

		$query = new AnnotationWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;
		$query->enabled = 'no';

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromAccessId() {

		$parts = [];
		$parts[] = $this->qb->expr()->eq('alias.access_id', ':qb1');
		$this->qb->param(ACCESS_PUBLIC, ELGG_VALUE_INTEGER);
		$expected = $this->qb->merge($parts);

		$query = new AnnotationWhereClause();
		$query->ignore_access = true;
		$query->use_enabled_clause = false;
		$query->access_ids = ACCESS_PUBLIC;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryWithAccessConstraint() {

		$parts = [];

		$access = new AccessWhereClause();
		$access->viewer_guid = 5;
		$parts[] = $access->prepare($this->qb, 'alias');

		$parts[] = $this->qb->expr()->eq('alias.entity_guid', ':qb4');
		$this->qb->param(1, ELGG_VALUE_INTEGER);

		$expected = $this->qb->merge($parts);

		$query = new AnnotationWhereClause();
		$query->viewer_guid = 5;
		$query->entity_guids = 1;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildSortByCalculationQuery() {

		$this->qb->addSelect("avg(CAST(alias.value AS DECIMAL(10, 2))) AS annotation_calculation");
		$this->qb->addGroupBy('alias.entity_guid');
		$this->qb->addOrderBy('annotation_calculation', 'desc');

		$parts = [];

		$access = new AccessWhereClause();
		$access->viewer_guid = 5;
		$parts[] = $access->prepare($this->qb, 'alias');
		$parts[] = $this->qb->expr()->eq('alias.entity_guid', ':qb4');
		$this->qb->param(1, ELGG_VALUE_INTEGER);
		$expr = $this->qb->merge($parts);
		$this->qb->andWhere($expr);

		$query = new AnnotationWhereClause();
		$query->viewer_guid = 5;
		$query->entity_guids = 1;
		$query->sort_by_calculation = 'avg';
		$query->sort_by_direction = 'desc';

		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	/**
	 * @expectedException \InvalidParameterException
	 */
	public function testThrowsOnInvalidSortByCalculation() {

		$query = new AnnotationWhereClause();
		$query->viewer_guid = 5;
		$query->entity_guids = 1;
		$query->sort_by_calculation = 'invalid';
		$query->sort_by_direction = 'desc';

		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);
	}

	public function testCanSortByTextValue() {

		$this->qb->addOrderBy('alias.value', 'desc');

		$parts = [];

		$access = new AccessWhereClause();
		$access->viewer_guid = 5;
		$parts[] = $access->prepare($this->qb, 'alias');
		$parts[] = $this->qb->expr()->eq('alias.entity_guid', ':qb4');
		$this->qb->param(1, ELGG_VALUE_INTEGER);
		$expr = $this->qb->merge($parts);
		$this->qb->andWhere($expr);

		$query = new AnnotationWhereClause();
		$query->viewer_guid = 5;
		$query->entity_guids = 1;
		$query->sort_by_direction = 'desc';

		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanSortByIntegerValue() {

		$this->qb->addOrderBy('CAST(alias.value AS SIGNED)', 'desc');

		$parts = [];

		$access = new AccessWhereClause();
		$access->viewer_guid = 5;
		$parts[] = $access->prepare($this->qb, 'alias');
		$parts[] = $this->qb->expr()->eq('alias.entity_guid', ':qb4');
		$this->qb->param(1, ELGG_VALUE_INTEGER);
		$expr = $this->qb->merge($parts);
		$this->qb->andWhere($expr);

		$query = new AnnotationWhereClause();
		$query->viewer_guid = 5;
		$query->entity_guids = 1;
		$query->sort_by_direction = 'desc';
		$query->value_type = ELGG_VALUE_INTEGER;

		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
}

