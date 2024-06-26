<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\EntityTable;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\UnitTestCase;

class AttributeWhereClauseUnitTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
	}

	public function down() {
		_elgg_services()->events->restore();
	}

	public function testBuildEmptyQuery() {

		$expected = null;

		$query = new AttributeWhereClause();

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, $qb->getTableAlias());

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromSingleAttributeName() {

		$parts = [];
		$parts[] = $this->qb->expr()->in('alias.attribute', ':qb1');
		$this->qb->param('value', ELGG_VALUE_STRING);

		$expected = $this->qb->merge($parts);

		$query = new AttributeWhereClause();
		$query->names = 'attribute';
		$query->values = ['value'];

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, $qb->getTableAlias());

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromMultipleAttributeNames() {

		$parts = [];
		$parts[] = $this->qb->expr()->in('alias.attribute1', ':qb1');
		$parts[] = $this->qb->expr()->in('alias.attribute2', ':qb2');
		$this->qb->param('value', ELGG_VALUE_STRING);
		$this->qb->param('value', ELGG_VALUE_STRING);

		$expected = $this->qb->merge($parts);

		$query = new AttributeWhereClause();
		$query->names = ['attribute1', 'attribute2'];
		$query->values = ['value'];

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, $qb->getTableAlias());

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
}
