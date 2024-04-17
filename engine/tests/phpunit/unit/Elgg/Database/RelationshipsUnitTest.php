<?php

namespace Elgg\Database;

use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\Database\Clauses\EntityWhereClause;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\MetadataWhereClause;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\Clauses\RelationshipWhereClause;
use Elgg\Exceptions\DataFormatException;
use Elgg\Exceptions\DomainException;
use Elgg\UnitTestCase;

class RelationshipsUnitTest extends UnitTestCase {

	public function testCanExecuteCount() {
		$select = Select::fromTable(RelationshipsTable::TABLE_NAME, 'er');
		$select->select("COUNT(DISTINCT {$select->getTableAlias()}.id) AS total");

		$select->addClause(new RelationshipWhereClause(), $select->getTableAlias());

		$select->joinEntitiesTable($select->getTableAlias(), 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');
		
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => [
				(object) [
					'total' => 10,
				]
			]
		]);

		$options = [
			'count' => true,
		];

		$find = Relationships::find($options);
		$count = Relationships::with($options)->count();

		$this->assertEquals(10, $find);
		$this->assertEquals(10, $count);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testThrowsOnBadDataFormat() {
		$this->expectException(DataFormatException::class);
		Relationships::find([
			'count' => true,
			'guids' => 'abc',
		]);
	}

	public function testCanExecuteGet() {
		$select = Select::fromTable(RelationshipsTable::TABLE_NAME, 'er');
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$select->addClause(new RelationshipWhereClause(), $select->getTableAlias());

		$select->joinEntitiesTable($select->getTableAlias(), 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$select->setMaxResults(5);
		$select->setFirstResult(5);
		$select->addOrderBy("{$select->getTableAlias()}.id", 'asc');

		$rows = $this->getRows(5);

		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'limit' => 5,
			'offset' => 5,
			'callback' => false,
			'order_by' => [
				new OrderByClause('er.id', 'ASC'),
			]
		];

		$find = Relationships::find($options);
		$get = Relationships::with($options)->get(5, 5, false);

		$this->assertEquals($rows, $find);
		$this->assertEquals($rows, $get);

		_elgg_services()->db->removeQuerySpec($spec);
	}
	
	/**
	 * @dataProvider orderBys
	 */
	public function testCanExecuteGetWithCorrectDefaultOrderBy($additional_options, $query_orders) {
		$select = Select::fromTable(RelationshipsTable::TABLE_NAME, 'er');
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$select->joinEntitiesTable($select->getTableAlias(), 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');
		
		foreach ($query_orders as $order_part => $direction) {
			$select->addOrderBy($order_part, $direction);
		}
		
		$rows = $this->getRows(5);
		
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);
		
		$options = [
			'limit' => false,
			'callback' => false,
		];
		
		$options = array_merge($options, $additional_options);
		
		$find = Relationships::find($options);
		
		$this->assertEquals($rows, $find);
		
		_elgg_services()->db->removeQuerySpec($spec);
	}
	
	public function testCanExecuteGetWithNoOrderByIfUsingSortBy() {
		$select = Select::fromTable(RelationshipsTable::TABLE_NAME, 'er');
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$select->join($select->getTableAlias(), EntityTable::TABLE_NAME, 'qbt1', "qbt1.guid = {$select->getTableAlias()}.guid_one");
		$select->joinEntitiesTable($select->getTableAlias(), 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');
		
		$select->addOrderBy('qbt1.time_created', 'desc');
		
		$rows = $this->getRows(5);
		
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);
		
		$find = Relationships::find([
			'limit' => false,
			'callback' => false,
			'sort_by' => [
				'property_type' => 'attribute',
				'property' => 'time_created',
				'direction' => 'desc',
			],
		]);
		
		// test default order by is not applied if sort_by is used
		$this->assertEquals($rows, $find);
		
		_elgg_services()->db->removeQuerySpec($spec);
	}
	
	public static function orderBys() {
		return [
			// test defaults are applied
			[
				[],
				[
					'er.time_created' => 'desc',
					'er.id' => 'desc',
				],
			],
			
			// test no default is applied if order by is disabled
			[
				['order_by' => false],
				[],
			],
			// test default only is applied if there is no custom order_by
			[
				['order_by' => RelationshipsTable::DEFAULT_JOIN_ALIAS . '.time_created asc'],
				[RelationshipsTable::DEFAULT_JOIN_ALIAS . '.time_created' => 'asc'],
			],
		];
	}

	public function testCanExecuteGetWithClauses() {
		$select = Select::fromTable(RelationshipsTable::TABLE_NAME, 'er');
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		$select->addSelect('max(e.time_created) AS newest');
		$select->groupBy("{$select->getTableAlias()}.guid_one");
		$select->join($select->getTableAlias(), MetadataTable::TABLE_NAME, 'n_table', "{$select->getTableAlias()}.guid_one = n_table.entity_guid");
		$alias = $select->joinMetadataTable('e', 'guid', 'status');
		$select->where($select->compare("{$alias}.value", 'IN', ['draft'], ELGG_VALUE_STRING));
		$select->having($select->compare('e.time_updated', 'IS NOT NULL'));
		$select->joinEntitiesTable($select->getTableAlias(), 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');
		$select->setMaxResults(5);
		$select->setFirstResult(5);
		
		$select->addOrderBy("{$select->getTableAlias()}.id", 'asc');

		$rows = $this->getRows(5);
		
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'limit' => 5,
			'offset' => 5,
			'callback' => false,
			'order_by' => [
				new OrderByClause('er.id', 'ASC'),
			],
			'selects' => [
				'max(e.time_created) AS newest',
			],
			'group_by' => [
				'er.guid_one',
			],
			'having' => [
				function (QueryBuilder $qb) {
					return $qb->compare('e.time_updated', 'IS NOT NULL');
				}
			],
			'joins' => [
				new JoinClause(MetadataTable::TABLE_NAME, 'n_table', 'er.guid_one = n_table.entity_guid'),
			],
			'wheres' => [
				function(QueryBuilder $qb) {
					$alias = $qb->joinMetadataTable('e', 'guid', 'status');
					return $qb->compare("{$alias}.value", 'IN', ['draft'], ELGG_VALUE_STRING);
				},
			]
		];
		
		$find = Relationships::find($options);
		$get = Relationships::with($options)->get(5, 5, false);
		
		$this->assertEquals($rows, $find);
		$this->assertEquals($rows, $get);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteBatchGet() {
		$select = Select::fromTable(RelationshipsTable::TABLE_NAME, 'er');
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$select->joinEntitiesTable($select->getTableAlias(), 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$select->setMaxResults(5);
		$select->setFirstResult(5);
		$select->addOrderBy("{$select->getTableAlias()}.id", 'asc');

		$rows = $this->getRows(5);

		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'limit' => 5,
			'offset' => 5,
			'callback' => false,
			'order_by' => [
				new OrderByClause('er.id', 'ASC'),
			],
			'batch' => true,
		];

		$find = Relationships::find($options);
		$batch = Relationships::with($options)->batch(5, 5, false);

		$this->assertInstanceOf(\ElggBatch::class, $find);
		$this->assertInstanceOf(\ElggBatch::class, $batch);

		foreach ($find as $i => $row) {
			$this->assertEquals($rows[$i], $row);
		}

		foreach ($batch as $i => $row) {
			$this->assertEquals($rows[$i], $row);
		}

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteAnnotationCalculation() {
		$annotation_names = ['foo'];

		$select = Select::fromTable(RelationshipsTable::TABLE_NAME, 'er');
		$select->select('min(a_table.value) AS calculation');
		
		$select->joinEntitiesTable($select->getTableAlias(), 'guid_one', 'inner', 'e');
		
		$wheres = [];
		
		$wheres[] = (new EntityWhereClause())->prepare($select, 'e');
		
		$alias = $select->joinAnnotationTable($select->getTableAlias(), 'guid_one', null, 'inner', AnnotationsTable::DEFAULT_JOIN_ALIAS);
		
		$annotation = new AnnotationWhereClause();
		$annotation->names = $annotation_names;
		
		$wheres[] = $annotation->prepare($select, $alias);
		
		$select->andWhere($select->merge($wheres));
				
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => [
				(object) [
					'calculation' => 10,
				]
			]
		]);

		$options = [
			'annotation_calculation' => 'min',
			'annotation_names' => $annotation_names,
		];

		$find = Relationships::find($options);
		$calculate = Relationships::with($options)->calculate('min', $annotation_names, 'annotation');

		$this->assertEquals(10, $find);
		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteMetadataCalculation() {
		$metadata_names = ['foo'];

		$select = Select::fromTable(RelationshipsTable::TABLE_NAME, 'er');

		$alias = $select->joinMetadataTable($select->getTableAlias(), 'guid_one', $metadata_names);
		$select->select("avg({$alias}.value) AS calculation");

		$wheres = [];
		
		$select->joinEntitiesTable($select->getTableAlias(), 'guid_one', 'inner', 'e');
		$wheres[] = (new EntityWhereClause())->prepare($select, 'e');

		$select->joinMetadataTable($select->getTableAlias(), 'guid_one', null, 'inner', 'md');
		
		$metadata = new MetadataWhereClause();
		$metadata->names = $metadata_names;
		$wheres[] = $metadata->prepare($select, 'md');
		
		$select->andWhere($select->merge($wheres));

		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => [
				(object) [
					'calculation' => 10,
				]
			]
		]);

		$options = [
			'metadata_calculation' => 'avg',
			'metadata_names' => $metadata_names,
		];

		$find = Relationships::find($options);
		$calculate = Relationships::with($options)->calculate('avg', $metadata_names, 'metadata');

		$this->assertEquals(10, $find);
		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testThrowsOnInvalidCalculation() {
		$this->expectException(DomainException::class);
		Relationships::with([])->calculate('invalid', 'status', 'annotation');
	}

	public function testCanExecuteAttributeCalculation() {
		$select = Select::fromTable(RelationshipsTable::TABLE_NAME, 'er');

		$select->select("max(e.guid) AS calculation");

		$select->joinEntitiesTable($select->getTableAlias(), 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => [
				(object) [
					'calculation' => 10,
				]
			]
		]);

		$calculate = Relationships::with([])->calculate('max', 'guid', 'attribute');

		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testThrowsOnInvalidAttributeCalculation() {
		$this->expectException(DomainException::class);
		Relationships::with([])->calculate('max', 'invalid', 'attribute');
	}

	public function testThrowsOnAnnotationsCalculationWithMultipleAndPairs() {
		$this->expectException(\LogicException::class);
		Relationships::find([
			'annotation_calculation' => 'min',
			'annotation_name_value_pairs' => [
				[
					'name' => 'status',
					'value' => 'draft',
				],
				[
					'name' => 'category',
					'value' => 'blogs',
				],
			],
		]);
	}

	public function testThrowsOnAnnotationCalculationWithMultipleAndPairs() {
		$this->expectException(\LogicException::class);
		Relationships::find([
			'annotation_calculation' => 'min',
			'annotation_name_value_pairs' => [
				[
					'name' => 'status',
					'value' => 'draft',
				],
				[
					'name' => 'category',
					'value' => 'blogs',
				],
			],
		]);
	}

	public function testCanExecuteQueryWithAnnotationsNameValuePairs() {
		$select = Select::fromTable(RelationshipsTable::TABLE_NAME, 'er');
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$wheres = [];
		
		$select->joinEntitiesTable($select->getTableAlias(), 'guid_one', 'inner', 'e');
		$where = new EntityWhereClause();
		$where->guids = [1, 2];
		$where->owner_guids = [3, 4];
		$where->container_guids = [5, 6];
		$wheres[] = $where->prepare($select, 'e');
		
		$an_wheres = [];
		$alias = $select->joinAnnotationTable($select->getTableAlias(), 'guid_one', ['foo1']);
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo1'];
		$annotation->values = ['bar1'];
		$annotation->ids = [1, 2];
		$annotation->owner_guids = [7, 8];
		$annotation->entity_guids = [1, 2];
		$an_wheres[] = $annotation->prepare($select, $alias);

		$alias = $select->joinAnnotationTable($select->getTableAlias(), 'guid_one', ['foo2']);
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo2'];
		$annotation->values = ['bar2'];
		$annotation->ids = [1, 2];
		$annotation->owner_guids = [7, 8];
		$annotation->entity_guids = [1, 2];
		$an_wheres[] = $annotation->prepare($select, $alias);

		$wheres[] = $select->merge($an_wheres);
		
		$select->andWhere($select->merge($wheres));
		
		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy("{$select->getTableAlias()}.id", 'asc');
		
		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'guids' => [1, 2],
			'owner_guids' => [3, 4],
			'container_guids' => [5, 6],
			'callback' => false,
			'annotation_name_value_pairs' => [
				'foo1' => 'bar1',
				'foo2' => 'bar2',
			],
			'annotation_ids' => [1, 2],
			'annotation_owner_guids' => [7, 8],
			'order_by' => [
				new OrderByClause('er.id', 'asc'),
			],
		];

		$find = Relationships::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithAnnotationsNameValuePairsJoinedByOr() {
		$select = Select::fromTable(RelationshipsTable::TABLE_NAME, 'er');
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$wheres = [];
		
		$alias = $select->joinEntitiesTable($select->getTableAlias(), 'guid_one', 'inner', EntityTable::DEFAULT_JOIN_ALIAS);
		$wheres[] = (new EntityWhereClause())->prepare($select, $alias);
		
		$alias = $select->joinAnnotationTable($select->getTableAlias(), 'guid_one', null, 'inner', AnnotationsTable::DEFAULT_JOIN_ALIAS);
		
		$an_wheres = [];
		
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo1'];
		$annotation->values = ['bar1'];
		$an_wheres[] = $annotation->prepare($select, $alias);

		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo2'];
		$annotation->values = ['bar2'];
		$an_wheres[] = $annotation->prepare($select, $alias);
		
		$wheres[] = $select->merge($an_wheres, 'OR');
		
		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy("{$select->getTableAlias()}.id", 'asc');

		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'callback' => false,
			'annotation_name_value_pairs' => [
				'foo1' => 'bar1',
				'foo2' => 'bar2',
			],
			'annotation_name_value_pairs_operator' => 'OR',
			'order_by' => [
				new OrderByClause('er.id', 'asc'),
			],
		];

		$find = Relationships::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithMetadataNameValuePairs() {
		$select = Select::fromTable(RelationshipsTable::TABLE_NAME, 'er');
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$wheres = [];
		
		$select->joinEntitiesTable($select->getTableAlias(), 'guid_one', 'inner', 'e');
		$wheres[] = (new EntityWhereClause())->prepare($select, 'e');

		$md_wheres = [];
		
		$alias1 = $select->joinMetadataTable($select->getTableAlias(), 'guid_one', ['foo1']);
		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo1'];
		$metadata->values = ['bar1'];
		$md_wheres[] = $metadata->prepare($select, $alias1);

		$alias2 = $select->joinMetadataTable($select->getTableAlias(), 'guid_one', ['foo2']);
		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo2'];
		$metadata->values = ['bar2'];
		$md_wheres[] = $metadata->prepare($select, $alias2);

		$wheres[] = $select->merge($md_wheres);
		
		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy("{$select->getTableAlias()}.id", 'asc');

		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'callback' => false,
			'metadata_name_value_pairs' => [
				'foo1' => 'bar1',
				'foo2' => 'bar2',
			],
			'order_by' => [
				new OrderByClause('er.id', 'asc'),
			],
		];

		$find = Relationships::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithMetadataNameValuePairsJoinedByOr() {
		$select = Select::fromTable(RelationshipsTable::TABLE_NAME, 'er');
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$wheres = [];
		
		$select->joinEntitiesTable($select->getTableAlias(), 'guid_one', 'inner', 'e');
		$wheres[] = (new EntityWhereClause())->prepare($select, 'e');

		$alias = $select->joinMetadataTable($select->getTableAlias(), 'guid_one', null, 'inner', 'md');
		
		$md_wheres = [];
		
		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo1'];
		$metadata->values = ['bar1'];
		$md_wheres[] = $metadata->prepare($select, $alias);

		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo2'];
		$metadata->values = ['bar2'];
		$md_wheres[] = $metadata->prepare($select, $alias);

		$wheres[] = $select->merge($md_wheres, 'OR');
		
		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy("{$select->getTableAlias()}.id", 'asc');
		
		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'callback' => false,
			'metadata_name_value_pairs' => [
				'foo1' => 'bar1',
				'foo2' => 'bar2',
			],
			'metadata_name_value_pairs_operator' => 'OR',
			'order_by' => [
				new OrderByClause('er.id', 'asc'),
			],
		];

		$find = Relationships::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithRelationshipPairs() {
		$select = Select::fromTable(RelationshipsTable::TABLE_NAME, 'er');
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$wheres = [];
		
		$select->joinEntitiesTable($select->getTableAlias(), 'guid_one', 'inner', 'e');
		$wheres[] = (new EntityWhereClause())->prepare($select, 'e');

		$r_wheres = [];
		
		$relationship = new RelationshipWhereClause();
		$relationship->names = ['foo1'];
		$relationship->subject_guids = [1, 2, 3];
		$r_wheres[] = $relationship->prepare($select, $select->getTableAlias());

		$relationship = new RelationshipWhereClause();
		$relationship->names = ['foo2'];
		$relationship->object_guids = [4, 5, 6];
		$relationship->inverse = true;
		$r_wheres[] = $relationship->prepare($select, $select->getTableAlias());

		$wheres[] = $select->merge($r_wheres);
		
		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy("{$select->getTableAlias()}.id", 'asc');

		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'callback' => false,
			'relationship_pairs' => [
				[
					'relationship' => 'foo1',
					'relationship_guid' => [1, 2, 3],
				],
				[
					'relationship' => 'foo2',
					'relationship_guid' => [4, 5, 6],
					'inverse_relationship' => true,
				]
			],
			'order_by' => [
				new OrderByClause('er.id', 'asc'),
			],
		];

		$find = Relationships::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithRelationship() {
		$select = Select::fromTable(RelationshipsTable::TABLE_NAME, 'er');
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$wheres = [];
		
		$select->joinEntitiesTable($select->getTableAlias(), 'guid_one', 'inner', 'e');
		$wheres[] = (new EntityWhereClause())->prepare($select, 'e');
		
		$relationship = new RelationshipWhereClause();
		$relationship->names = ['foo1'];
		$relationship->subject_guids = [1, 2, 3];
		$wheres[] = $relationship->prepare($select, $select->getTableAlias());

		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy("{$select->getTableAlias()}.id", 'asc');

		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'callback' => false,
			'relationship_guid' => [1, 2, 3],
			'relationship' => ['foo1'],
			'inverse_relationship' => false,
			'order_by' => [
				new OrderByClause('er.id', 'asc'),
			],
		];

		$find = Relationships::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	protected function getRows($limit = 10) {
		$rows = [];
		for ($i = 0; $i < $limit; $i++) {
			$row = (object) [
				'id' => $i,
				'guid_one' => rand(100, 999),
				'relationship' => 'relationship_' . rand(1, 100),
				'guid_two' => rand(1, 999),
				'time_created' => time(),
			];
			$rows[] = $row;
		}

		return $rows;
	}
}
