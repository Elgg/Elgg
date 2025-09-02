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

class AnnotationsUnitTest extends UnitTestCase {

	public function testCanExecuteCount() {
		$select = Select::fromTable(AnnotationsTable::TABLE_NAME, AnnotationsTable::DEFAULT_JOIN_ALIAS);
		$select->select("COUNT(DISTINCT {$select->getTableAlias()}.id) AS total");

		$wheres = [];
		
		$wheres[] = (new AnnotationWhereClause())->prepare($select, $select->getTableAlias());
		
		$select->join($select->getTableAlias(), EntityTable::TABLE_NAME, 'e', "e.guid = {$select->getTableAlias()}.entity_guid");
		
		$wheres[] = (new EntityWhereClause())->prepare($select, 'e');
		$select->andWhere($select->merge($wheres));

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

		$find = Annotations::find($options);
		$count = Annotations::with($options)->count();

		$this->assertEquals(10, $find);
		$this->assertEquals(10, $count);

		_elgg_services()->db->removeQuerySpec($spec);
	}
	
	public function testThrowsOnBadDataFormat() {
		$this->expectException(DataFormatException::class);
		Annotations::find([
			'count' => true,
			'guids' => 'abc',
		]);
	}

	public function testCanExecuteGet() {
		$select = Select::fromTable(AnnotationsTable::TABLE_NAME, AnnotationsTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$select->addClause(new AnnotationWhereClause(), $select->getTableAlias());

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
				new OrderByClause('a_table.id', 'ASC'),
			]
		];

		$find = Annotations::find($options);
		$get = Annotations::with($options)->get(5, 5, false);

		$this->assertEquals($rows, $find);
		$this->assertEquals($rows, $get);

		_elgg_services()->db->removeQuerySpec($spec);
	}
	
	/**
	 * @dataProvider orderBys
	 */
	public function testCanExecuteGetWithCorrectDefaultOrderBy($additional_options, $query_orders) {
		$select = Select::fromTable(AnnotationsTable::TABLE_NAME, AnnotationsTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$select->addClause(new AnnotationWhereClause(), $select->getTableAlias());
		
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
		
		$find = Annotations::find($options);
		
		$this->assertEquals($rows, $find);
		
		_elgg_services()->db->removeQuerySpec($spec);
	}
	
	public function testCanExecuteGetWithNoOrderByIfUsingSortBy() {
		$select = Select::fromTable(AnnotationsTable::TABLE_NAME, AnnotationsTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$select->addClause(new AnnotationWhereClause(), $select->getTableAlias());
		
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
		
		$find = Annotations::find([
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
					AnnotationsTable::DEFAULT_JOIN_ALIAS . '.time_created' => 'asc',
					AnnotationsTable::DEFAULT_JOIN_ALIAS . '.id' => 'asc',
				],
			],
			
			// test no default is applied if order by is disabled
			[
				['order_by' => false],
				[],
			],
			// test default only is applied if there is no custom order_by
			[
				['order_by' => AnnotationsTable::DEFAULT_JOIN_ALIAS . '.time_created desc'],
				[AnnotationsTable::DEFAULT_JOIN_ALIAS . '.time_created' => 'desc'],
			],
		];
	}
	
	public function testCanExecuteGetWithClauses() {
		$select = Select::fromTable(AnnotationsTable::TABLE_NAME, AnnotationsTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		$select->addSelect('max(e.time_created) AS newest');
		$select->groupBy("{$select->getTableAlias()}.entity_guid");
		$select->join($select->getTableAlias(), AnnotationsTable::TABLE_NAME, 'an', "{$select->getTableAlias()}.entity_guid = an.entity_guid");
		$alias = $select->joinAnnotationTable($select->getTableAlias(), 'entity_guid', 'status');
		$select->where($select->compare("{$alias}.value", 'IN', ['draft'], ELGG_VALUE_STRING));
		$select->having($select->compare('e.time_updated', 'IS NOT NULL'));

		$select->addClause(new AnnotationWhereClause(), $select->getTableAlias());

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
				new OrderByClause('a_table.id', 'ASC'),
			],
			'selects' => [
				'max(e.time_created) AS newest',
			],
			'group_by' => [
				'a_table.entity_guid',
			],
			'having' => [
				function (QueryBuilder $qb) {
					return $qb->compare('e.time_updated', 'IS NOT NULL');
				}
			],
			'joins' => [
				new JoinClause(AnnotationsTable::TABLE_NAME, 'an', 'a_table.entity_guid = an.entity_guid', 'inner'),
			],
			'wheres' => [
				function (QueryBuilder $qb, $main_alias) {
					$alias = $qb->joinAnnotationTable($main_alias, 'entity_guid', 'status');

					return $qb->compare("{$alias}.value", 'IN', ['draft'], ELGG_VALUE_STRING);
				}
			]
		];

		$find = Annotations::find($options);
		$get = Annotations::with($options)->get(5, 5, false);

		$this->assertEquals($rows, $find);
		$this->assertEquals($rows, $get);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteBatchGet() {
		$select = Select::fromTable(AnnotationsTable::TABLE_NAME, AnnotationsTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$select->addClause(new AnnotationWhereClause(), $select->getTableAlias());

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
				new OrderByClause('a_table.id', 'ASC'),
			],
			'batch' => true,
		];

		$find = Annotations::find($options);
		$batch = Annotations::with($options)->batch(5, 5, false);

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

		$select = Select::fromTable(AnnotationsTable::TABLE_NAME, AnnotationsTable::DEFAULT_JOIN_ALIAS);
		$select->select("min({$select->getTableAlias()}.value) AS calculation");
		
		$wheres = [];
		
		$annotation = AnnotationWhereClause::factory(['names' => $annotation_names]);

		$wheres[] = $annotation->prepare($select, $select->getTableAlias());
		
		$select->join($select->getTableAlias(), EntityTable::TABLE_NAME, 'e', "e.guid = {$select->getTableAlias()}.entity_guid");
		
		$wheres[] = (new EntityWhereClause())->prepare($select, 'e');
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

		$find = Annotations::find($options);
		$calculate = Annotations::with($options)->calculate('min', $annotation_names, 'annotation');

		$this->assertEquals(10, $find);
		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteAnnotationsCalculationByAnnotationsNotInAnnotationWheres() {
		$annotation_names = ['foo'];
		$annotation_calculation_names = ['bar'];

		$select = Select::fromTable(AnnotationsTable::TABLE_NAME, AnnotationsTable::DEFAULT_JOIN_ALIAS);

		$alias = $select->joinAnnotationTable($select->getTableAlias(), 'entity_guid', $annotation_calculation_names);
		$select->select("min({$alias}.value) AS calculation");
		
		$wheres = [];
		
		$annotation = AnnotationWhereClause::factory(['names' => $annotation_names]);

		$wheres[] = $annotation->prepare($select, $select->getTableAlias());
		
		$select->join($select->getTableAlias(), EntityTable::TABLE_NAME, 'e', "e.guid = {$select->getTableAlias()}.entity_guid");

		$wheres[] = (new EntityWhereClause())->prepare($select, 'e');
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

		$calculate = Annotations::with($options)->calculate('min', $annotation_calculation_names, 'annotation');

		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteAnnotationsCalculationWithoutPropertyType() {
		$annotation_name = 'foo';

		$select = Select::fromTable(AnnotationsTable::TABLE_NAME, AnnotationsTable::DEFAULT_JOIN_ALIAS);
		$select->select("min({$select->getTableAlias()}.value) AS calculation");

		$select->addClause(new AnnotationWhereClause(), $select->getTableAlias());

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

		$calculate = Annotations::with([])->calculate('min', $annotation_name);

		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteMetadataCalculation() {
		$metadata_names = ['foo'];

		$select = Select::fromTable(AnnotationsTable::TABLE_NAME, AnnotationsTable::DEFAULT_JOIN_ALIAS);

		$md_alias = $select->joinMetadataTable($select->getTableAlias(), 'entity_guid', $metadata_names);
		$select->select("avg({$md_alias}.value) AS calculation");

		$select->addClause(new AnnotationWhereClause(), $select->getTableAlias());

		$e_alias = $select->joinEntitiesTable($select->getTableAlias(), 'entity_guid', 'inner', EntityTable::DEFAULT_JOIN_ALIAS);
		$select->addClause(new EntityWhereClause(), $e_alias);

		$md_alias2 = $select->joinMetadataTable($select->getTableAlias(), 'entity_guid', null, 'inner', MetadataTable::DEFAULT_JOIN_ALIAS);
		$metadata = MetadataWhereClause::factory(['names' => $metadata_names]);
		$select->addClause($metadata, $md_alias2);

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

		$find = Annotations::find($options);
		$calculate = Annotations::with($options)->calculate('avg', $metadata_names, 'metadata');

		$this->assertEquals(10, $find);
		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testThrowsOnInvalidCalculation() {
		$this->expectException(DomainException::class);
		Annotations::with([])->calculate('invalid', 'status', 'annotation');
	}

	public function testCanExecuteAttributeCalculation() {
		$select = Select::fromTable(AnnotationsTable::TABLE_NAME, AnnotationsTable::DEFAULT_JOIN_ALIAS);

		$select->select("max(e.guid) AS calculation");

		$select->addClause(new AnnotationWhereClause(), $select->getTableAlias());

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

		$calculate = Annotations::with([])->calculate('max', 'guid', 'attribute');

		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testThrowsOnInvalidAttributeCalculation() {
		$this->expectException(DomainException::class);
		Annotations::with([])->calculate('max', 'invalid', 'attribute');
	}

	public function testThrowsOnAnnotationsCalculationWithMultipleAndPairs() {
		$this->expectException(\LogicException::class);
		Annotations::find([
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
		Annotations::find([
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
		$select = Select::fromTable(AnnotationsTable::TABLE_NAME, AnnotationsTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$annotation_wheres = [];
		
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo1'];
		$annotation->values = ['bar1'];
		$annotation->ids = [1, 2];
		$annotation->owner_guids = [7, 8];
		$annotation->entity_guids = [1, 2];
		$annotation_wheres[] = $annotation->prepare($select, $select->getTableAlias());

		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo2'];
		$annotation->values = ['bar2'];
		$annotation->ids = [1, 2];
		$annotation->owner_guids = [7, 8];
		$annotation->entity_guids = [1, 2];
		$annotation_wheres[] = $annotation->prepare($select, $select->getTableAlias());
		
		$wheres = [];
		$wheres[] = $select->merge($annotation_wheres);
		
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
			'annotation_name_value_pairs' => [
				'foo1' => 'bar1',
				'foo2' => 'bar2',
			],
			'annotation_ids' => [1, 2],
			'annotation_owner_guids' => [7, 8],
			'order_by' => [
				new OrderByClause('a_table.id', 'asc'),
			],
		];

		$find = Annotations::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithAnnotationSortByCalculation() {
		$select = Select::fromTable(AnnotationsTable::TABLE_NAME, AnnotationsTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$wheres = [];
		
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo1'];
		$annotation->values = ['bar1'];
		$annotation->ids = [1, 2];
		$annotation->owner_guids = [7, 8];
		$annotation->entity_guids = [1, 2];
		$annotation->sort_by_calculation = 'avg';
		$wheres[] = $annotation->prepare($select, $select->getTableAlias());

		$select->join($select->getTableAlias(), EntityTable::TABLE_NAME, 'e', "e.guid = {$select->getTableAlias()}.entity_guid");
		$where = new EntityWhereClause();
		$where->guids = [1, 2];
		$where->owner_guids = [3, 4];
		$where->container_guids = [5, 6];
		$wheres[] = $where->prepare($select, 'e');
		
		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('annotation_calculation', 'desc');
		$select->addOrderBy("{$select->getTableAlias()}.time_created", 'asc');
		$select->addOrderBy("{$select->getTableAlias()}.id", 'asc');

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
			],
			'annotation_ids' => [1, 2],
			'annotation_owner_guids' => [7, 8],
			'annotation_sort_by_calculation' => 'avg',
		];

		$find = Annotations::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithAnnotationsNameValuePairsJoinedByOr() {
		$select = Select::fromTable(AnnotationsTable::TABLE_NAME, AnnotationsTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$wheres = [];
		
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo1'];
		$annotation->values = ['bar1'];
		$wheres[] = $annotation->prepare($select, $select->getTableAlias());

		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo2'];
		$annotation->values = ['bar2'];
		$wheres[] = $annotation->prepare($select, $select->getTableAlias());

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
			'annotation_name_value_pairs' => [
				'foo1' => 'bar1',
				'foo2' => 'bar2',
			],
			'annotation_name_value_pairs_operator' => 'OR',
			'order_by' => [
				new OrderByClause('a_table.id', 'asc'),
			],
		];

		$find = Annotations::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithMetadataNameValuePairs() {
		$select = Select::fromTable(AnnotationsTable::TABLE_NAME, AnnotationsTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$wheres = [];
		
		$select->addClause(new AnnotationWhereClause(), $select->getTableAlias());

		$select->joinEntitiesTable($select->getTableAlias(), 'entity_guid', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$alias1 = $select->joinMetadataTable($select->getTableAlias(), 'entity_guid', ['foo1']);
		$metadata = MetadataWhereClause::factory([
			'names' => ['foo1'],
			'values' => ['bar1'],
		]);
		$wheres[] = $metadata->prepare($select, $alias1);

		$alias2 = $select->joinMetadataTable($select->getTableAlias(), 'entity_guid', ['foo2']);
		$metadata = MetadataWhereClause::factory([
			'names' => ['foo2'],
			'values' => ['bar2'],
		]);
		$wheres[] = $metadata->prepare($select, $alias2);

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
				new OrderByClause('a_table.id', 'asc'),
			],
		];

		$find = Annotations::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithMetadataNameValuePairsJoinedByOr() {
		$select = Select::fromTable(AnnotationsTable::TABLE_NAME, AnnotationsTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$wheres = [];
		
		$select->addClause(new AnnotationWhereClause(), $select->getTableAlias());

		$select->joinEntitiesTable($select->getTableAlias(), 'entity_guid', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$alias = $select->joinMetadataTable($select->getTableAlias(), 'entity_guid', null, 'inner', MetadataTable::DEFAULT_JOIN_ALIAS);

		$metadata = MetadataWhereClause::factory([
			'names' => ['foo1'],
			'values' => ['bar1'],
		]);
		$wheres[] = $metadata->prepare($select, $alias);

		$metadata = MetadataWhereClause::factory([
			'names' => ['foo2'],
			'values' => ['bar2'],
		]);
		$wheres[] = $metadata->prepare($select, $alias);

		$select->andWhere($select->merge($wheres, 'OR'));

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
				new OrderByClause('a_table.id', 'asc'),
			],
		];

		$find = Annotations::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithRelationshipPairs() {
		$select = Select::fromTable(AnnotationsTable::TABLE_NAME, AnnotationsTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$wheres = [];
		
		$select->addClause(new AnnotationWhereClause(), $select->getTableAlias());

		$select->joinEntitiesTable($select->getTableAlias(), 'entity_guid', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$alias1 = $select->joinRelationshipTable($select->getTableAlias(), 'entity_guid', ['foo1']);
		$rel1 = RelationshipWhereClause::factory([
			'names' => ['foo1'],
			'guid_one' => [1, 2, 3],
		]);
		$wheres[] = $rel1->prepare($select, $alias1);

		$alias2 = $select->joinRelationshipTable($select->getTableAlias(), 'entity_guid', ['foo2'], true);
		$rel2 = RelationshipWhereClause::factory([
			'names' => ['foo2'],
			'guid_two' => [4, 5, 6],
		]);
		$wheres[] = $rel2->prepare($select, $alias2);

		$select->andWhere($select->expr()->and(...$wheres));

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
				new OrderByClause('a_table.id', 'asc'),
			],
		];

		$find = Annotations::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithRelationship() {
		$select = Select::fromTable(AnnotationsTable::TABLE_NAME, AnnotationsTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$wheres = [];
		
		$select->addClause(new AnnotationWhereClause(), $select->getTableAlias());

		$select->joinEntitiesTable($select->getTableAlias(), 'entity_guid', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$alias = $select->joinRelationshipTable($select->getTableAlias(), 'entity_guid', null, false, 'inner', RelationshipsTable::DEFAULT_JOIN_ALIAS);

		$rel = RelationshipWhereClause::factory([
			'names' => ['foo1'],
			'guid_one' => [1, 2, 3],
		]);
		$wheres[] = $rel->prepare($select, $alias);

		$select->andWhere($select->merge($wheres, 'OR'));

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
				new OrderByClause('a_table.id', 'asc'),
			],
		];

		$find = Annotations::find($options);

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
