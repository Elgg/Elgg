<?php

namespace Elgg\Integration;

use Elgg\Database\Clauses\PrivateSettingWhereClause;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\UnitTestCase;

/**
 * @group QueryBuilder
 * @group QueryBuilderWhere
 */
class PrivateSettingWhereClauseTest extends UnitTestCase {

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

		$query = new PrivateSettingWhereClause();

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

		$query = new PrivateSettingWhereClause();
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

		$query = new PrivateSettingWhereClause();
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

		$query = new PrivateSettingWhereClause();
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
		$parts[] = $this->qb->expr()->like('alias.value', ':qb2');
		$this->qb->param(['foo1', 'foo2'], ELGG_VALUE_STRING);
		$this->qb->param('%bar%', ELGG_VALUE_STRING);
		$expected = $this->qb->merge($parts);

		$query = new PrivateSettingWhereClause();
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

		$query = new PrivateSettingWhereClause();
		$query->entity_guids = 1;

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
}

