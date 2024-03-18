<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\EntityTable;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\Exceptions\DomainException;
use Elgg\UnitTestCase;

class AnnotationWhereClauseUnitTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
	}

	public function testBuildEmptyQuery() {
		$expected = null;

		$query = new AnnotationWhereClause();
		$query->ignore_access = true;

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, $qb->getTableAlias());

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromId() {
		$parts = [];
		$parts[] = $this->qb->expr()->eq("{$this->qb->getTableAlias()}.id", ':qb1');
		$this->qb->param(1, ELGG_VALUE_INTEGER);
		$expected = $this->qb->merge($parts);

		$query = new AnnotationWhereClause();
		$query->ignore_access = true;
		$query->ids = 1;

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, $qb->getTableAlias());

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromName() {
		$parts = [];
		$parts[] = $this->qb->expr()->in("{$this->qb->getTableAlias()}.name", ':qb1');
		$this->qb->param(['foo1', 'foo2'], ELGG_VALUE_STRING);
		$expected = $this->qb->merge($parts);

		$query = new AnnotationWhereClause();
		$query->ignore_access = true;
		$query->names = ['foo1', 'foo2'];

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, $qb->getTableAlias());

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromValue() {
		$parts = [];
		$parts[] = $this->qb->expr()->in("{$this->qb->getTableAlias()}.value", ':qb1');
		$this->qb->param(['foo1', 'foo2'], ELGG_VALUE_STRING);
		$expected = $this->qb->merge($parts);

		$query = new AnnotationWhereClause();
		$query->ignore_access = true;
		$query->values = ['foo1', 'foo2'];
		$query->value_type = ELGG_VALUE_STRING;
		$query->case_sensitive = false;

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, $qb->getTableAlias());

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromNameValueWithComparison() {
		$parts = [];
		$parts[] = $this->qb->expr()->in("{$this->qb->getTableAlias()}.name", ':qb1');
		$parts[] = $this->qb->expr()->like("{$this->qb->getTableAlias()}.value", 'BINARY :qb2');
		$this->qb->param(['foo1', 'foo2'], ELGG_VALUE_STRING);
		$this->qb->param('%bar%', ELGG_VALUE_STRING);
		$expected = $this->qb->merge($parts);

		$query = new AnnotationWhereClause();
		$query->ignore_access = true;
		$query->names = ['foo1', 'foo2'];
		$query->values = '%bar%';
		$query->value_type = ELGG_VALUE_STRING;
		$query->comparison = 'like';

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, $qb->getTableAlias());

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromGuid() {
		$parts = [];
		$parts[] = $this->qb->expr()->eq("{$this->qb->getTableAlias()}.entity_guid", ':qb1');
		$this->qb->param(1, ELGG_VALUE_INTEGER);
		$expected = $this->qb->merge($parts);

		$query = new AnnotationWhereClause();
		$query->ignore_access = true;
		$query->entity_guids = 1;

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, $qb->getTableAlias());

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromOwnerGuid() {
		$parts = [];
		$parts[] = $this->qb->expr()->in("{$this->qb->getTableAlias()}.owner_guid", ':qb1');
		$this->qb->param([2, 3], ELGG_VALUE_INTEGER);
		$expected = $this->qb->merge($parts);

		$query = new AnnotationWhereClause();
		$query->ignore_access = true;
		$query->owner_guids = [2, 3];

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, $qb->getTableAlias());

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromTimeCreated() {
		$after = (new \DateTime())->modify('-1 day');
		$before = (new \DateTime())->modify('+1 day');

		$parts = [];

		$time_parts = [];
		$time_parts[] = $this->qb->expr()->gte("{$this->qb->getTableAlias()}.time_created", ':qb1');
		$time_parts[] = $this->qb->expr()->lte("{$this->qb->getTableAlias()}.time_created", ':qb2');
		$this->qb->param($after->getTimestamp(), ELGG_VALUE_INTEGER);
		$this->qb->param($before->getTimestamp(), ELGG_VALUE_INTEGER);
		$parts[] = $this->qb->merge($time_parts);

		$expected = $this->qb->merge($parts);

		$query = new AnnotationWhereClause();
		$query->ignore_access = true;
		$query->created_after = $after;
		$query->created_before = $before;

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, $qb->getTableAlias());

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromAccessId() {
		$parts = [];
		$parts[] = $this->qb->expr()->eq("{$this->qb->getTableAlias()}.access_id", ':qb1');
		$this->qb->param(ACCESS_PUBLIC, ELGG_VALUE_INTEGER);
		$expected = $this->qb->merge($parts);

		$query = new AnnotationWhereClause();
		$query->ignore_access = true;
		$query->access_ids = ACCESS_PUBLIC;

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, $qb->getTableAlias());

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryWithAccessConstraint() {
		$parts = [];

		$access = new AccessWhereClause();
		$access->viewer_guid = 5;
		$access->use_enabled_clause = false;
		$parts[] = $access->prepare($this->qb, 'alias');

		$parts[] = $this->qb->expr()->eq("{$this->qb->getTableAlias()}.entity_guid", ':qb3');
		$this->qb->param(1, ELGG_VALUE_INTEGER);

		$expected = $this->qb->merge($parts);

		$query = new AnnotationWhereClause();
		$query->viewer_guid = 5;
		$query->entity_guids = 1;

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, $qb->getTableAlias());

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildSortByCalculationQuery() {
		$this->qb->addSelect("avg(CAST({$this->qb->getTableAlias()}.value AS DECIMAL(10, 2))) AS annotation_calculation");
		$this->qb->addGroupBy("{$this->qb->getTableAlias()}.entity_guid");
		$this->qb->addOrderBy('annotation_calculation', 'desc');

		$parts = [];

		$access = new AccessWhereClause();
		$access->viewer_guid = 5;
		$access->use_enabled_clause = false;
		
		$parts[] = $access->prepare($this->qb, 'alias');
		$parts[] = $this->qb->expr()->eq("{$this->qb->getTableAlias()}.entity_guid", ':qb3');
		$this->qb->param(1, ELGG_VALUE_INTEGER);
		$expr = $this->qb->merge($parts);
		$this->qb->andWhere($expr);

		$query = new AnnotationWhereClause();
		$query->viewer_guid = 5;
		$query->entity_guids = 1;
		$query->sort_by_calculation = 'avg';
		$query->sort_by_direction = 'desc';

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testThrowsOnInvalidSortByCalculation() {
		$query = new AnnotationWhereClause();
		$query->viewer_guid = 5;
		$query->entity_guids = 1;
		$query->sort_by_calculation = 'invalid';
		$query->sort_by_direction = 'desc';

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		
		$this->expectException(DomainException::class);
		$qb->addClause($query);
	}

	public function testCanSortByTextValue() {
		$this->qb->addOrderBy("{$this->qb->getTableAlias()}.value", 'desc');

		$parts = [];

		$access = new AccessWhereClause();
		$access->viewer_guid = 5;
		$access->use_enabled_clause = false;
		
		$parts[] = $access->prepare($this->qb, 'alias');
		$parts[] = $this->qb->expr()->eq("{$this->qb->getTableAlias()}.entity_guid", ':qb3');
		$this->qb->param(1, ELGG_VALUE_INTEGER);
		$expr = $this->qb->merge($parts);
		$this->qb->andWhere($expr);

		$query = new AnnotationWhereClause();
		$query->viewer_guid = 5;
		$query->entity_guids = 1;
		$query->sort_by_direction = 'desc';

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testCanSortByIntegerValue() {
		$this->qb->addOrderBy("CAST({$this->qb->getTableAlias()}.value AS SIGNED)", 'desc');

		$parts = [];

		$access = new AccessWhereClause();
		$access->viewer_guid = 5;
		$access->use_enabled_clause = false;
		
		$parts[] = $access->prepare($this->qb, $this->qb->getTableAlias());
		$parts[] = $this->qb->expr()->eq("{$this->qb->getTableAlias()}.entity_guid", ':qb3');
		$this->qb->param(1, ELGG_VALUE_INTEGER);
		$expr = $this->qb->merge($parts);
		$this->qb->andWhere($expr);

		$query = new AnnotationWhereClause();
		$query->viewer_guid = 5;
		$query->entity_guids = 1;
		$query->sort_by_direction = 'desc';
		$query->value_type = ELGG_VALUE_INTEGER;

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
}
