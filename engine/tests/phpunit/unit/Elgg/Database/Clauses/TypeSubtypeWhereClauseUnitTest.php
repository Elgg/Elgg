<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\EntityTable;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\UnitTestCase;

class TypeSubtypeWhereClauseUnitTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
	}

	public function testBuildQueryFromSingleType() {
		$parts = [];

		$type_where = [];
		$type_where[] = $this->qb->expr()->eq('alias.type', ':qb1');
		$this->qb->param('object', ELGG_VALUE_STRING);
		$parts[] = $this->qb->merge($type_where, 'OR');

		$expected = $this->qb->merge($parts);

		$query = new TypeSubtypeWhereClause();
		$query->type_subtype_pairs = ['object' => []];

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, $qb->getTableAlias());

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromMultipleTypes() {
		$parts = [];

		$type_where = [];
		$type_where[] = $this->qb->expr()->eq('alias.type', ':qb1');
		$type_where[] = $this->qb->expr()->eq('alias.type', ':qb2');
		$this->qb->param('object', ELGG_VALUE_STRING);
		$this->qb->param('group', ELGG_VALUE_STRING);
		$parts[] = $this->qb->merge($type_where, 'OR');

		$expected = $this->qb->merge($parts);

		$query = new TypeSubtypeWhereClause();
		$query->type_subtype_pairs = [
			'object' => [],
			'group' => null,
		];

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, $qb->getTableAlias());

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromSingleTypeWithSubtypes() {
		$parts = [];

		$type_where = [];
		$type_where[] = $this->qb->merge([
			$this->qb->expr()->eq('alias.type', ':qb1'),
			$this->qb->expr()->in('alias.subtype', ':qb2'),
		]);
		$this->qb->param('object', ELGG_VALUE_STRING);
		$this->qb->param(['blog', 'file'], ELGG_VALUE_STRING);
		$parts[] = $this->qb->merge($type_where, 'OR');

		$expected = $this->qb->merge($parts);

		$query = new TypeSubtypeWhereClause();
		$query->type_subtype_pairs = ['object' => ['blog', 'file']];

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, $qb->getTableAlias());

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromMultipleTypesWithMixedSubtypes() {
		$parts = [];

		$type_where = [];
		$type_where[] = $this->qb->merge([
			$this->qb->expr()->eq('alias.type', ':qb1'),
			$this->qb->expr()->in('alias.subtype', ':qb2'),
		]);
		$type_where[] = $this->qb->expr()->eq('alias.type', ':qb3');

		$this->qb->param('object', ELGG_VALUE_STRING);
		$this->qb->param(['blog', 'file'], ELGG_VALUE_STRING);
		$this->qb->param('group', ELGG_VALUE_STRING);
		$parts[] = $this->qb->merge($type_where, 'OR');

		$expected = $this->qb->merge($parts);

		$query = new TypeSubtypeWhereClause();
		$query->type_subtype_pairs = [
			'object' => ['blog', 'file'],
			'group' => [],
		];

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, $qb->getTableAlias());

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildQueryFromMultipleTypesWithSubtypes() {
		$parts = [];

		$type_where = [];
		$type_where[] = $this->qb->merge([
			$this->qb->expr()->eq('alias.type', ':qb1'),
			$this->qb->expr()->in('alias.subtype', ':qb2'),
		]);
		$type_where[] = $this->qb->merge([
			$this->qb->expr()->eq('alias.type', ':qb3'),
			$this->qb->expr()->in('alias.subtype', ':qb4'),
		]);

		$this->qb->param('object', ELGG_VALUE_STRING);
		$this->qb->param(['blog', 'file'], ELGG_VALUE_STRING);
		$this->qb->param('group', ELGG_VALUE_STRING);
		$this->qb->param('community', ELGG_VALUE_STRING);
		$parts[] = $this->qb->merge($type_where, 'OR');

		$expected = $this->qb->merge($parts);

		$query = new TypeSubtypeWhereClause();
		$query->type_subtype_pairs = [
			'object' => ['blog', 'file'],
			'group' => ['community'],
		];

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$actual = $query->prepare($qb, $qb->getTableAlias());

		$this->assertEquals($expected, $actual);
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
}
