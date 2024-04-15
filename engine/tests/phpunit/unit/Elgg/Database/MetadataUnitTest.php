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

class MetadataUnitTest extends UnitTestCase {

	public function testCanExecuteCount() {
		$select = Select::fromTable(MetadataTable::TABLE_NAME, MetadataTable::DEFAULT_JOIN_ALIAS);
		$select->select("COUNT(DISTINCT {$select->getTableAlias()}.id) AS total");

		$select->join($select->getTableAlias(), EntityTable::TABLE_NAME, 'e', "e.guid = {$select->getTableAlias()}.entity_guid");
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

		$find = Metadata::find($options);
		$count = Metadata::with($options)->count();

		$this->assertEquals(10, $find);
		$this->assertEquals(10, $count);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testThrowsOnBadDataFormat() {
		$this->expectException(DataFormatException::class);
		Metadata::find([
			'count' => true,
			'guids' => 'abc',
		]);
	}

	public function testCanExecuteGet() {
		$select = Select::fromTable(MetadataTable::TABLE_NAME, MetadataTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$select->join($select->getTableAlias(), EntityTable::TABLE_NAME, 'e', "e.guid = {$select->getTableAlias()}.entity_guid");
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
				new OrderByClause('n_table.id', 'ASC'),
			]
		];

		$find = Metadata::find($options);
		$get = Metadata::with($options)->get(5, 5, false);

		$this->assertEquals($rows, $find);
		$this->assertEquals($rows, $get);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	/**
	 * @dataProvider orderBys
	 */
	public function testCanExecuteGetWithCorrectDefaultOrderBy($additional_options, $query_orders) {
		$select = Select::fromTable(MetadataTable::TABLE_NAME, MetadataTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$select->join($select->getTableAlias(), EntityTable::TABLE_NAME, 'e', "e.guid = {$select->getTableAlias()}.entity_guid");
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
		
		$find = Metadata::find($options);
		
		$this->assertEquals($rows, $find);
		
		_elgg_services()->db->removeQuerySpec($spec);
	}
	
	public function testCanExecuteGetWithNoOrderByIfUsingSortBy() {
		$select = Select::fromTable(MetadataTable::TABLE_NAME, MetadataTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$select->join($select->getTableAlias(), EntityTable::TABLE_NAME, 'qbt1', "qbt1.guid = {$select->getTableAlias()}.entity_guid");
		$select->join($select->getTableAlias(), EntityTable::TABLE_NAME, 'e', "e.guid = {$select->getTableAlias()}.entity_guid");
		$select->addClause(new EntityWhereClause(), 'e');

		$select->addOrderBy('qbt1.time_created', 'desc');

		$rows = $this->getRows(5);
		
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);
		
		$find = Metadata::find([
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
					MetadataTable::DEFAULT_JOIN_ALIAS . '.time_created' => 'asc',
					MetadataTable::DEFAULT_JOIN_ALIAS . '.id' => 'asc',
				],
			],
			
			// test no default is applied if order by is disabled
			[
				['order_by' => false],
				[],
			],
			// test default only is applied if there is no custom order_by
			[
				['order_by' => MetadataTable::DEFAULT_JOIN_ALIAS . '.time_created desc'],
				[MetadataTable::DEFAULT_JOIN_ALIAS . '.time_created' => 'desc'],
			],
		];
	}

	public function testCanExecuteGetWithClauses() {
		$select = Select::fromTable(MetadataTable::TABLE_NAME, MetadataTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		$select->addSelect('max(e.time_created) AS newest');
		$select->groupBy("{$select->getTableAlias()}.entity_guid");
		$select->join($select->getTableAlias(), AnnotationsTable::TABLE_NAME, 'an', "{$select->getTableAlias()}.entity_guid = an.entity_guid");
		$alias = $select->joinMetadataTable($select->getTableAlias(), 'entity_guid', 'status');
		$select->where($select->compare("{$alias}.value", 'IN', ['draft'], ELGG_VALUE_STRING));
		$select->having($select->compare('e.time_updated', 'IS NOT NULL'));


		$select->join($select->getTableAlias(), EntityTable::TABLE_NAME, 'e', "e.guid = {$select->getTableAlias()}.entity_guid");
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
				new OrderByClause('n_table.id', 'ASC'),
			],
			'selects' => [
				'max(e.time_created) AS newest',
			],
			'group_by' => [
				'n_table.entity_guid',
			],
			'having' => [
				function (QueryBuilder $qb) {
					return $qb->compare('e.time_updated', 'IS NOT NULL');
				}
			],
			'joins' => [
				new JoinClause(AnnotationsTable::TABLE_NAME, 'an', 'n_table.entity_guid = an.entity_guid', 'inner'),
			],
			'wheres' => [
				function (QueryBuilder $qb, $main_alias) {
					$alias = $qb->joinMetadataTable($main_alias, 'entity_guid', 'status');

					return $qb->compare("{$alias}.value", 'IN', ['draft'], ELGG_VALUE_STRING);
				}
			]
		];

		$find = Metadata::find($options);
		$get = Metadata::with($options)->get(5, 5, false);

		$this->assertEquals($rows, $find);
		$this->assertEquals($rows, $get);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteBatchGet() {
		$select = Select::fromTable(MetadataTable::TABLE_NAME, MetadataTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$select->join($select->getTableAlias(), EntityTable::TABLE_NAME, 'e', "e.guid = {$select->getTableAlias()}.entity_guid");
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
				new OrderByClause('n_table.id', 'ASC'),
			],
			'batch' => true,
		];

		$find = Metadata::find($options);
		$batch = Metadata::with($options)->batch(5, 5, false);

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

	public function testCanExecuteMetadataCalculation() {
		$metadata_names = ['foo'];
		
		$select = Select::fromTable(MetadataTable::TABLE_NAME, MetadataTable::DEFAULT_JOIN_ALIAS);
		$select->select("min({$select->getTableAlias()}.value) AS calculation");

		$metadata = new MetadataWhereClause();
		$metadata->names = $metadata_names;
		$select->addClause($metadata, $select->getTableAlias());

		$select->join($select->getTableAlias(), EntityTable::TABLE_NAME, 'e', "e.guid = {$select->getTableAlias()}.entity_guid");
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

		$options = [
			'metadata_calculation' => 'min',
			'metadata_names' => $metadata_names,
		];

		$find = Metadata::find($options);
		$calculate = Metadata::with($options)->calculate('min', $metadata_names, 'metadata');

		$this->assertEquals(10, $find);
		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteMetadataCalculationByMetadataNotInMetadataWheres() {
		$metadata_names = ['foo'];
		$metadata_calculation_names = ['bar'];

		$select = Select::fromTable(MetadataTable::TABLE_NAME, MetadataTable::DEFAULT_JOIN_ALIAS);

		$alias = $select->joinMetadataTable($select->getTableAlias(), 'entity_guid', $metadata_calculation_names);
		$select->select("min({$alias}.value) AS calculation");

		$metadata = new MetadataWhereClause();
		$metadata->names = $metadata_names;
		$select->addClause($metadata, $select->getTableAlias());

		$select->join($select->getTableAlias(), EntityTable::TABLE_NAME, 'e', "e.guid = {$select->getTableAlias()}.entity_guid");
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

		$options = [
			'metadata_calculation' => 'min',
			'metadata_names' => $metadata_names,
		];

		$calculate = Metadata::with($options)->calculate('min', $metadata_calculation_names, 'metadata');

		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteMetadataCalculationWithoutPropertyType() {
		$metadata_name = 'foo';

		$select = Select::fromTable(MetadataTable::TABLE_NAME, MetadataTable::DEFAULT_JOIN_ALIAS);
		$select->select("min({$select->getTableAlias()}.value) AS calculation");

		$select->join($select->getTableAlias(), EntityTable::TABLE_NAME, 'e', "e.guid = {$select->getTableAlias()}.entity_guid");
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

		$calculate = Metadata::with([])->calculate('min', $metadata_name);

		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteAnnotationCalculation() {
		$annotation_names = ['foo'];

		$select = Select::fromTable(MetadataTable::TABLE_NAME, MetadataTable::DEFAULT_JOIN_ALIAS);

		$alias = $select->joinAnnotationTable($select->getTableAlias(), 'entity_guid', $annotation_names, 'inner', AnnotationsTable::DEFAULT_JOIN_ALIAS);
		$select->select("avg({$alias}.value) AS calculation");

		$wheres = [];
		
		$select->join($select->getTableAlias(), EntityTable::TABLE_NAME, 'e', "e.guid = {$select->getTableAlias()}.entity_guid");
		$wheres[] = (new EntityWhereClause())->prepare($select, 'e');

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
			'annotation_calculation' => 'avg',
			'annotation_names' => $annotation_names,
		];

		$find = Metadata::find($options);
		$calculate = Metadata::with($options)->calculate('avg', $annotation_names, 'annotation');

		$this->assertEquals(10, $find);
		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testThrowsOnInvalidCalculation() {
		$this->expectException(DomainException::class);
		Metadata::with([])->calculate('invalid', 'status', 'metadata');
	}

	public function testCanExecuteAttributeCalculation() {
		$select = Select::fromTable(MetadataTable::TABLE_NAME, MetadataTable::DEFAULT_JOIN_ALIAS);

		$select->select("max(e.guid) AS calculation");

		$select->join($select->getTableAlias(), EntityTable::TABLE_NAME, 'e', "e.guid = {$select->getTableAlias()}.entity_guid");
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

		$calculate = Metadata::with([])->calculate('max', 'guid', 'attribute');

		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testThrowsOnInvalidAttributeCalculation() {
		$this->expectException(DomainException::class);
		Metadata::with([])->calculate('max', 'invalid', 'attribute');
	}

	public function testThrowsOnMetadataCalculationWithMultipleAndPairs() {
		$this->expectException(\LogicException::class);
		Metadata::find([
			'metadata_calculation' => 'min',
			'metadata_name_value_pairs' => [
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
		Metadata::find([
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

	public function testCanExecuteQueryWithMetadataNameValuePairs() {
		$select = Select::fromTable(MetadataTable::TABLE_NAME, MetadataTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$wheres = [];
		
		$md_wheres = [];
		
		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo1'];
		$metadata->values = ['bar1'];
		$metadata->ids = [1, 2];
		$metadata->entity_guids = [1, 2];
		$md_wheres[] = $metadata->prepare($select, $select->getTableAlias());

		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo2'];
		$metadata->values = ['bar2'];
		$metadata->ids = [1, 2];
		$metadata->entity_guids = [1, 2];
		$md_wheres[] = $metadata->prepare($select, $select->getTableAlias());

		$wheres[] = $select->merge($md_wheres);

		$select->join($select->getTableAlias(), EntityTable::TABLE_NAME, 'e', "e.guid = {$select->getTableAlias()}.entity_guid");
		$where = new EntityWhereClause();
		$where->guids = [1, 2];
		$where->owner_guids = [3, 4];
		$where->container_guids = [5, 6];
		
		$wheres[] = $where->prepare($select, 'e');
		
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
			'metadata_name_value_pairs' => [
				'foo1' => 'bar1',
				'foo2' => 'bar2',
			],
			'metadata_ids' => [1, 2],
			'order_by' => [
				new OrderByClause('n_table.id', 'asc'),
			],
		];

		$find = Metadata::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithMetadataNameValuePairsJoinedByOr() {
		$select = Select::fromTable(MetadataTable::TABLE_NAME, MetadataTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$wheres = [];
		
		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo1'];
		$metadata->values = ['bar1'];
		$wheres[] = $metadata->prepare($select, $select->getTableAlias());

		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo2'];
		$metadata->values = ['bar2'];
		$wheres[] = $metadata->prepare($select, $select->getTableAlias());

		$select->andWhere($select->merge($wheres, 'OR'));

		$select->join($select->getTableAlias(), EntityTable::TABLE_NAME, 'e', "e.guid = {$select->getTableAlias()}.entity_guid");
		$select->addClause(new EntityWhereClause(), 'e');

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
				new OrderByClause('n_table.id', 'asc'),
			],
		];

		$find = Metadata::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithAnnotationNameValuePairs() {
		$select = Select::fromTable(MetadataTable::TABLE_NAME, MetadataTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$wheres = [];
		
		$alias = $select->joinEntitiesTable($select->getTableAlias(), 'entity_guid', 'inner', EntityTable::DEFAULT_JOIN_ALIAS);
		$wheres[] = (new EntityWhereClause())->prepare($select, $alias);

		$an_wheres = [];
		$alias1 = $select->joinAnnotationTable($select->getTableAlias(), 'entity_guid', ['foo1']);
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo1'];
		$annotation->values = ['bar1'];
		$an_wheres[] = $annotation->prepare($select, $alias1);

		$alias2 = $select->joinAnnotationTable($select->getTableAlias(), 'entity_guid', ['foo2']);
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo2'];
		$annotation->values = ['bar2'];
		$an_wheres[] = $annotation->prepare($select, $alias2);

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
			'callback' => false,
			'annotation_name_value_pairs' => [
				'foo1' => 'bar1',
				'foo2' => 'bar2',
			],
			'order_by' => [
				new OrderByClause('n_table.id', 'asc'),
			],
		];

		$find = Metadata::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithAnnotationNameValuePairsJoinedByOr() {
		$select = Select::fromTable(MetadataTable::TABLE_NAME, MetadataTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$wheres = [];
		
		$select->joinEntitiesTable($select->getTableAlias(), 'entity_guid', 'inner', 'e');
		$wheres[] = (new EntityWhereClause())->prepare($select, 'e');

		$an_wheres = [];
		$alias = $select->joinAnnotationTable($select->getTableAlias(), 'entity_guid', null, 'inner', AnnotationsTable::DEFAULT_JOIN_ALIAS);

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
				new OrderByClause('n_table.id', 'asc'),
			],
		];

		$find = Metadata::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithRelationshipPairs() {
		$select = Select::fromTable(MetadataTable::TABLE_NAME, MetadataTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$wheres = [];
		
		$select->joinEntitiesTable($select->getTableAlias(), 'entity_guid', 'inner', 'e');
		$wheres[] = (new EntityWhereClause())->prepare($select, 'e');

		$r_wheres = [];
		
		$alias1 = $select->joinRelationshipTable($select->getTableAlias(), 'entity_guid', ['foo1']);
		$rel1 = new RelationshipWhereClause();
		$rel1->names = ['foo1'];
		$rel1->subject_guids = [1, 2, 3];
		$r_wheres[] = $rel1->prepare($select, $alias1);

		$alias2 = $select->joinRelationshipTable($select->getTableAlias(), 'entity_guid', ['foo2'], true);
		$rel2 = new RelationshipWhereClause();
		$rel2->names = ['foo2'];
		$rel2->object_guids = [4, 5, 6];
		$r_wheres[] = $rel2->prepare($select, $alias2);

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
				new OrderByClause('n_table.id', 'asc'),
			],
		];

		$find = Metadata::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithRelationship() {
		$select = Select::fromTable(MetadataTable::TABLE_NAME, MetadataTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$wheres = [];
		
		$select->joinEntitiesTable($select->getTableAlias(), 'entity_guid', 'inner', 'e');
		$wheres[] = (new EntityWhereClause())->prepare($select, 'e');

		$alias = $select->joinRelationshipTable($select->getTableAlias(), 'entity_guid', null, false, 'inner', RelationshipsTable::DEFAULT_JOIN_ALIAS);

		$rel = new RelationshipWhereClause();
		$rel->names = ['foo1'];
		$rel->subject_guids = [1, 2, 3];
		$wheres[] = $rel->prepare($select, $alias);

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
				new OrderByClause('n_table.id', 'asc'),
			],
		];

		$find = Metadata::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function getRows($limit = 10) {
		$rows = [];
		for ($i = 0; $i < $limit; $i++) {
			$row = (object) [
				'id' => $i,
				'entity_guid' => rand(100, 999),
				'name' => 'name_' . rand(1, 100),
				'value' => 'value_' . rand(1, 100),
				'time_created' => time(),
				'time_updated' => null,
			];
			$rows[] = $row;
		}

		return $rows;
	}
}
