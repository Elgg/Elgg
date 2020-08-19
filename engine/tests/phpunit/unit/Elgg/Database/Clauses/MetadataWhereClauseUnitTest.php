<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\UnitTestCase;

/**
 * @group QueryBuilder
 * @group QueryBuilderWhere
 */
class MetadataWhereClauseUnitTest extends UnitTestCase {

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

		$query = new MetadataWhereClause();

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

		$query = new MetadataWhereClause();
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

		$query = new MetadataWhereClause();
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

		$query = new MetadataWhereClause();
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

		$query = new MetadataWhereClause();
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

		$query = new MetadataWhereClause();
		$query->entity_guids = 1;

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

		$query = new MetadataWhereClause();
		$query->created_after = $after;
		$query->created_before = $before;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());

	}
}

