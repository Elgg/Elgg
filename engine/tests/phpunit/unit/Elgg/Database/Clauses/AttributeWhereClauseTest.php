<?php

namespace Elgg\Integration;

use Elgg\Database\Clauses\AttributeWhereClause;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\UnitTestCase;

/**
 * @group QueryBuilder
 * @group QueryBuilderWhere
 */
class AttributeWhereClauseTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable('entities', 'alias');
	}

	public function down() {
		_elgg_services()->session->removeLoggedInUser();
		_elgg_services()->hooks->restore();
	}

	public function testBuildEmptyQuery() {

		$expected = null;

		$query = new AttributeWhereClause();

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromSingleAttributeName() {

		$parts = [];
		$parts[] = $this->qb->expr()->eq('alias.attribute', ':qb1');
		$this->qb->param('value', ELGG_VALUE_STRING);

		$expected = $this->qb->merge($parts);

		$query = new AttributeWhereClause();
		$query->names = 'attribute';
		$query->values = ['value'];

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromMultipleAttributeNames() {

		$parts = [];
		$parts[] = $this->qb->expr()->eq('alias.attribute1', ':qb1');
		$parts[] = $this->qb->expr()->eq('alias.attribute2', ':qb2');
		$this->qb->param('value', ELGG_VALUE_STRING);
		$this->qb->param('value', ELGG_VALUE_STRING);

		$expected = $this->qb->merge($parts);

		$query = new AttributeWhereClause();
		$query->names = ['attribute1', 'attribute2'];
		$query->values = ['value'];

		$qb = Select::fromTable('entities', 'alias');
		$actual = $query->prepare($qb, 'alias');

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
}
