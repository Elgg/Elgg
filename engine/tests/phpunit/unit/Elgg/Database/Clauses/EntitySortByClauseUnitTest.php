<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\EntityTable;
use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\Exceptions\DomainException;
use Elgg\UnitTestCase;

class EntitySortByClauseUnitTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$this->qb->select('*');
	}

	public function testBuildAttributeSortByClause() {

		$this->qb->orderBy("{$this->qb->getTableAlias()}.guid", 'asc');

		$query = new EntitySortByClause();
		$query->property = 'guid';
		$query->direction = 'asc';

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->select('*');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildSignedAttributeSortByClause() {

		$this->qb->orderBy("CAST({$this->qb->getTableAlias()}.guid AS SIGNED)", 'asc');

		$query = new EntitySortByClause();
		$query->property = 'guid';
		$query->direction = 'asc';
		$query->signed = true;

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->select('*');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildMetadataSortByClause() {

		$alias = $this->qb->joinMetadataTable($this->qb->getTableAlias(), 'guid', 'foo');
		$this->qb->orderBy("{$alias}.value", 'asc');

		$query = new EntitySortByClause();
		$query->property = 'foo';
		$query->direction = 'asc';

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->select('*');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildSignedMetadataSortByClause() {

		$alias = $this->qb->joinMetadataTable($this->qb->getTableAlias(), 'guid', 'foo');
		$this->qb->orderBy("CAST({$alias}.value AS SIGNED)", 'asc');

		$query = new EntitySortByClause();
		$query->property = 'foo';
		$query->direction = 'asc';
		$query->signed = true;

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->select('*');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildAnnotationSortByClause() {

		$alias = $this->qb->joinAnnotationTable($this->qb->getTableAlias(), 'guid', 'foo');
		$this->qb->orderBy("{$alias}.value", 'asc');

		$query = new EntitySortByClause();
		$query->property = 'foo';
		$query->direction = 'asc';
		$query->property_type = 'annotation';

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->select('*');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildSignedAnnotationSortByClause() {

		$alias = $this->qb->joinAnnotationTable($this->qb->getTableAlias(), 'guid', 'foo');
		$this->qb->orderBy("CAST({$alias}.value AS SIGNED)", 'asc');

		$query = new EntitySortByClause();
		$query->property = 'foo';
		$query->direction = 'asc';
		$query->signed = true;
		$query->property_type = 'annotation';
		
		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->select('*');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testThrowsOnInvalidAttributeName() {

		$query = new EntitySortByClause();
		$query->property = 'invalid';
		$query->direction = 'asc';
		$query->property_type = 'attribute';

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->select('*');
		
		$this->expectException(DomainException::class);
		$qb->addClause($query);
	}

	public function testThrowsOnInvalidPropertyType() {

		$query = new EntitySortByClause();
		$query->property = 'foo';
		$query->direction = 'asc';
		$query->property_type = 'invalid';

		$qb = Select::fromTable(EntityTable::TABLE_NAME, 'alias');
		$qb->select('*');
		
		_elgg_services()->logger->disable();
		$this->assertNull($query->prepare($qb, $qb->getTableAlias()));
		$log = _elgg_services()->logger->enable();
		$this->assertEquals("'invalid' is not a valid entity property type. Sorting ignored.", $log[0]['message']);
	}
}
