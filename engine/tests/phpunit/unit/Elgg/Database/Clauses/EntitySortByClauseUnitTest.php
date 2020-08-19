<?php

namespace Elgg\Database\Clauses;

use Elgg\Database\QueryBuilder;
use Elgg\Database\Select;
use Elgg\Exceptions\InvalidParameterException;
use Elgg\UnitTestCase;

/**
 * @group QueryBuilder
 */
class EntitySortByClauseUnitTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	protected $qb;

	public function up() {
		$this->qb = Select::fromTable('entities', 'alias');
	}

	public function down() {

	}

	public function testBuildAttributeSortByClause() {

		$this->qb->orderBy('alias.guid', 'asc');

		$query = new EntitySortByClause();
		$query->property = 'guid';
		$query->direction = 'asc';

		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildSignedAttributeSortByClause() {

		$this->qb->orderBy('CAST(alias.guid AS SIGNED)', 'asc');

		$query = new EntitySortByClause();
		$query->property = 'guid';
		$query->direction = 'asc';
		$query->signed = true;

		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildMetadataSortByClause() {

		$alias = $this->qb->joinMetadataTable('alias', 'guid', 'foo');
		$this->qb->orderBy("$alias.value", 'asc');

		$query = new EntitySortByClause();
		$query->property = 'foo';
		$query->direction = 'asc';

		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildSignedMetadataSortByClause() {

		$alias = $this->qb->joinMetadataTable('alias', 'guid', 'foo');
		$this->qb->orderBy("CAST($alias.value AS SIGNED)", 'asc');

		$query = new EntitySortByClause();
		$query->property = 'foo';
		$query->direction = 'asc';
		$query->signed = true;

		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildAnnotationSortByClause() {

		$alias = $this->qb->joinAnnotationTable('alias', 'guid', 'foo');
		$this->qb->orderBy("$alias.value", 'asc');

		$query = new EntitySortByClause();
		$query->property = 'foo';
		$query->direction = 'asc';
		$query->property_type = 'annotation';

		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildSignedAnnotationSortByClause() {

		$alias = $this->qb->joinAnnotationTable('alias', 'guid', 'foo');
		$this->qb->orderBy("CAST($alias.value AS SIGNED)", 'asc');

		$query = new EntitySortByClause();
		$query->property = 'foo';
		$query->direction = 'asc';
		$query->signed = true;
		$query->property_type = 'annotation';
		
		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildPrivateSettingSortByClause() {

		$alias = $this->qb->joinPrivateSettingsTable('alias', 'guid', 'foo');
		$this->qb->orderBy("$alias.value", 'asc');

		$query = new EntitySortByClause();
		$query->property = 'foo';
		$query->direction = 'asc';
		$query->property_type = 'private_setting';

		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}

	public function testBuildSignedPrivateSettingSortByClause() {

		$alias = $this->qb->joinPrivateSettingsTable('alias', 'guid', 'foo');
		$this->qb->orderBy("CAST($alias.value AS SIGNED)", 'asc');

		$query = new EntitySortByClause();
		$query->property = 'foo';
		$query->direction = 'asc';
		$query->signed = true;
		$query->property_type = 'private_setting';

		$qb = Select::fromTable('entities', 'alias');
		$qb->addClause($query);

		$this->assertEquals($this->qb->getSQL(), $qb->getSQL());
		$this->assertEquals($this->qb->getParameters(), $qb->getParameters());
	}
	
	public function testThrowsOnInvalidAttributeName() {

		$query = new EntitySortByClause();
		$query->property = 'invalid';
		$query->direction = 'asc';
		$query->property_type = 'attribute';

		$qb = Select::fromTable('entities', 'alias');
		
		$this->expectException(InvalidParameterException::class);
		$qb->addClause($query);
	}

	public function testThrowsOnInvalidPropertyType() {

		$query = new EntitySortByClause();
		$query->property = 'foo';
		$query->direction = 'asc';
		$query->property_type = 'invalid';

		$qb = Select::fromTable('entities', 'alias');
		
		$this->expectException(InvalidParameterException::class);
		$qb->addClause($query);
	}
}
