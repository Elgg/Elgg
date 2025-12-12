<?php

namespace Elgg\Database;

use Elgg\Database\Clauses\AccessWhereClause;
use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\MetadataWhereClause;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\Clauses\RelationshipWhereClause;
use Elgg\Exceptions\DataFormatException;
use Elgg\Exceptions\DomainException;
use Elgg\UnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class EntitiesUnitTest extends UnitTestCase {

	public function testCanExecuteCount() {
		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("COUNT(DISTINCT {$select->getTableAlias()}.guid) AS total");

		$select->addClause(new AccessWhereClause());

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

		$find = Entities::find($options);
		$count = Entities::with($options)->count();

		$this->assertEquals(10, $find);
		$this->assertEquals(10, $count);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testThrowsOnBadDataFormat() {
		$this->expectException(DataFormatException::class);
		Entities::find([
			'count' => true,
			'guids' => 'abc',
		]);
	}

	public function testCanExecuteGet() {
		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$select->addClause(new AccessWhereClause());

		$select->setMaxResults(5);
		$select->setFirstResult(5);
		$select->addOrderBy("{$select->getTableAlias()}.guid", 'asc');

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
				new OrderByClause('e.guid', 'ASC'),
			]
		];

		$find = Entities::find($options);
		$get = Entities::with($options)->get(5, 5, false);

		$this->assertEquals($rows, $find);
		$this->assertEquals($rows, $get);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	#[DataProvider('orderBys')]
	public function testCanExecuteGetWithCorrectDefaultOrderBy($additional_options, $query_orders) {
		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$select->addClause(new AccessWhereClause());
		
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
		
		$find = Entities::find($options);
		
		$this->assertEquals($rows, $find);
		
		_elgg_services()->db->removeQuerySpec($spec);
	}
	
	public function testCanExecuteGetWithNoOrderByIfUsingSortBy() {
		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$select->addClause(new AccessWhereClause());
		
		$select->addOrderBy('e.time_created', 'desc');
		
		$rows = $this->getRows(5);
		
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);
		
		$find = Entities::find([
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
					EntityTable::DEFAULT_JOIN_ALIAS . '.time_created' => 'desc',
					EntityTable::DEFAULT_JOIN_ALIAS . '.guid' => 'desc',
				],
			],
			
			// test no default is applied if order by is disabled
			[
				['order_by' => false],
				[],
			],
			// test default only is applied if there is no custom order_by
			[
				['order_by' => EntityTable::DEFAULT_JOIN_ALIAS . '.time_created asc'],
				[EntityTable::DEFAULT_JOIN_ALIAS . '.time_created' => 'asc'],
			],
		];
	}
	
	public function testCanExecuteGetWithClauses() {
		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		$select->addSelect("max({$select->getTableAlias()}.time_created) AS newest");
		$select->groupBy("{$select->getTableAlias()}.time_created");
		$select->join($select->getTableAlias(), MetadataTable::TABLE_NAME, 'n_table', "{$select->getTableAlias()}.guid = n_table.entity_guid");
		$alias = $select->joinMetadataTable($select->getTableAlias(), 'guid', 'status');
		$select->where($select->compare("{$alias}.value", 'IN', ['draft'], ELGG_VALUE_STRING));
		$select->having($select->compare("{$select->getTableAlias()}.time_updated", 'IS NOT NULL'));
		$select->addClause(new AccessWhereClause());

		$select->setMaxResults(5);
		$select->setFirstResult(5);
		$select->addOrderBy("{$select->getTableAlias()}.guid", 'asc');

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
				new OrderByClause('e.guid', 'ASC'),
			],
			'selects' => [
				'max(e.time_created) AS newest',
			],
			'group_by' => [
				'e.time_created',
			],
			'having' => [
				function(QueryBuilder $qb, $main_alias) {
				return $qb->compare("{$main_alias}.time_updated", 'IS NOT NULL');
			}
			],
			'joins' => [
				new JoinClause(MetadataTable::TABLE_NAME, 'n_table', 'e.guid = n_table.entity_guid'),
			],
			'wheres' =>[
				function(QueryBuilder $qb, $main_alias) {
					$alias = $qb->joinMetadataTable($main_alias, 'guid', 'status');
					return $qb->compare("{$alias}.value", 'IN', ['draft'], ELGG_VALUE_STRING);
				}
			]
		];

		$find = Entities::find($options);
		$get = Entities::with($options)->get(5, 5, false);

		$this->assertEquals($rows, $find);
		$this->assertEquals($rows, $get);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteBatchGet() {
		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$access = new AccessWhereClause();
		$access_where = $access->prepare($select, 'e');
		$select->andWhere($access_where);

		$select->setMaxResults(5);
		$select->setFirstResult(5);
		$select->addOrderBy("{$select->getTableAlias()}.guid", 'asc');

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
				new OrderByClause('e.guid', 'ASC'),
			],
			'batch' => true,
		];

		$find = Entities::find($options);
		$batch = Entities::with($options)->batch(5, 5, false);

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
		
		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->joinMetadataTable($select->getTableAlias(), 'guid', $metadata_names, 'inner', 'n_table');
		$select->select("min(n_table.value) AS calculation");

		$wheres = [];
		$wheres[] = (new AccessWhereClause())->prepare($select, EntityTable::DEFAULT_JOIN_ALIAS);

		$metadata = MetadataWhereClause::factory(['names' => $metadata_names]);
		$wheres[] = $metadata->prepare($select, 'n_table');

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
			'metadata_calculation' => 'min',
			'metadata_names' => $metadata_names,
		];

		$find = Entities::find($options);
		$calculate = Entities::with($options)->calculate('min', $metadata_names, 'metadata');

		$this->assertEquals(10, $find);
		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteMetadataCalculationWithoutPropertyType() {
		$metadata_name = 'foo';
		
		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->joinMetadataTable($select->getTableAlias(), 'guid', $metadata_name, 'inner', 'n_table');
		$select->select("min(n_table.value) AS calculation");

		$select->addClause(new AccessWhereClause());

		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => [
				(object) [
					'calculation' => 10,
				]
			]
		]);

		$calculate = Entities::with([])->calculate('min', $metadata_name);

		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}
	
	public function testCanExecuteAnnotationCalculation() {
		$annotation_names = ['foo'];
		
		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$alias = $select->joinAnnotationTable($select->getTableAlias(), 'guid', $annotation_names, 'inner', AnnotationsTable::DEFAULT_JOIN_ALIAS);
		$select->select("avg({$alias}.value) AS calculation");

		$wheres = [];
		$wheres[] = (new AccessWhereClause())->prepare($select, EntityTable::DEFAULT_JOIN_ALIAS);
		
		$annotation = new AnnotationWhereClause();
		$annotation->names = $annotation_names;
		$wheres[] =  $annotation->prepare($select, $alias);
		
		$select->andwhere($select->merge($wheres));

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

		$find = Entities::find($options);
		$calculate = Entities::with($options)->calculate('avg', $annotation_names, 'annotation');

		$this->assertEquals(10, $find);
		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testThrowsOnInvalidCalculation() {
		$this->expectException(DomainException::class);
		Entities::with([])->calculate('invalid', 'guid', 'attribute');
	}

	public function testCanExecuteAttributeCalculation() {
		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("max({$select->getTableAlias()}.guid) AS calculation");

		$select->addClause(new AccessWhereClause());

		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => [
				(object) [
					'calculation' => 10,
				]
			]
		]);

		$calculate = Entities::with([])->calculate('max', 'guid', 'attribute');

		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteAttributeCalculationWithoutPropertyType() {
		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("max({$select->getTableAlias()}.guid) AS calculation");

		$select->addClause(new AccessWhereClause());

		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => [
				(object) [
					'calculation' => 10,
				]
			]
		]);

		$calculate = Entities::with([])->calculate('max', 'guid');

		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testThrowsOnInvalidAttributeCalculation() {
		$this->expectException(DomainException::class);
		Entities::with([])->calculate('max', 'invalid', 'attribute');
	}

	public function testThrowsOnMetadataCalculationWithMultipleAndPairs() {
		$options = [
			'metadata_calculation' => 'min',
			'metadata_name_value_pairs' => [
				[
					'name' => 'status',
					'value' => 'draft',
				],
				[
					'name' => 'category',
					'value' => 'blogs',
				]
			]
		];

		$this->expectException(\LogicException::class);
		Entities::find($options);
	}

	public function testThrowsOnAnnotationCalculationWithMultipleAndPairs() {
		$options = [
			'annotation_calculation' => 'min',
			'annotation_name_value_pairs' => [
				[
					'name' => 'status',
					'value' => 'draft',
				],
				[
					'name' => 'category',
					'value' => 'blogs',
				]
			]
		];

		$this->expectException(\LogicException::class);
		Entities::find($options);
	}

	public function testCanExecuteQueryWithMetadataNameValuePairs() {
		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$wheres = [];
		
		$wheres[] = (new AccessWhereClause())->prepare($select, EntityTable::DEFAULT_JOIN_ALIAS);
		
		$md_wheres = [];
		
		$alias1 = $select->joinMetadataTable($select->getTableAlias(), 'guid', ['foo1']);
		$metadata = MetadataWhereClause::factory([
			'names' => ['foo1'],
			'values' => ['bar1'],
		]);
		$md_wheres[] = $metadata->prepare($select, $alias1);

		$alias2 = $select->joinMetadataTable($select->getTableAlias(), 'guid', ['foo2']);
		$metadata = MetadataWhereClause::factory([
			'names' => ['foo2'],
			'values' => ['bar2'],
		]);
		$md_wheres[] = $metadata->prepare($select, $alias2);
		
		$wheres[] = $select->merge($md_wheres);
		
		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy("{$select->getTableAlias()}.guid", 'asc');

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
				new OrderByClause('e.guid', 'asc'),
			],
		];

		$find = Entities::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithMetadataNameValuePairsJoinedByOr() {
		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$wheres = [];
		
		$wheres[] = (new AccessWhereClause())->prepare($select, EntityTable::DEFAULT_JOIN_ALIAS);

		$select->joinMetadataTable($select->getTableAlias(), 'guid', null, 'inner','n_table');
		
		$md_wheres = [];
		
		$metadata = MetadataWhereClause::factory([
			'names' => ['foo1'],
			'values' => ['bar1'],
		]);
		$md_wheres[] = $metadata->prepare($select, 'n_table');

		$metadata = MetadataWhereClause::factory([
			'names' => ['foo2'],
			'values' => ['bar2'],
		]);
		$md_wheres[] = $metadata->prepare($select, 'n_table');

		$wheres[] = $select->merge($md_wheres, 'OR');
		
		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy("{$select->getTableAlias()}.guid", 'asc');

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
				new OrderByClause('e.guid', 'asc'),
			],
		];

		$find = Entities::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithAnnotationNameValuePairs() {
		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$wheres = [];
		
		$wheres[] = (new AccessWhereClause())->prepare($select, EntityTable::DEFAULT_JOIN_ALIAS);
		
		$an_wheres = [];

		$alias1 = $select->joinAnnotationTable($select->getTableAlias(), 'guid', ['foo1']);
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo1'];
		$annotation->values = ['bar1'];
		$an_wheres[] = $annotation->prepare($select, $alias1);

		$alias2 = $select->joinAnnotationTable($select->getTableAlias(), 'guid', ['foo2']);
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo2'];
		$annotation->values = ['bar2'];
		$an_wheres[] = $annotation->prepare($select, $alias2);

		$wheres[] = $select->merge($an_wheres);
		
		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy("{$select->getTableAlias()}.guid", 'asc');

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
				new OrderByClause('e.guid', 'asc'),
			],
		];

		$find = Entities::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithAnnotationNameValuePairsJoinedByOr() {
		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$wheres = [];
		
		$wheres[] = (new AccessWhereClause())->prepare($select, EntityTable::DEFAULT_JOIN_ALIAS);
		
		$an_wheres = [];

		$alias = $select->joinAnnotationTable($select->getTableAlias(), 'guid', null, 'inner', AnnotationsTable::DEFAULT_JOIN_ALIAS);

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

		$select->orderBy("{$select->getTableAlias()}.guid", 'asc');

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
				new OrderByClause('e.guid', 'asc'),
			],
		];

		$find = Entities::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithRelationshipPairs() {
		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$wheres = [];
		
		$wheres[] = (new AccessWhereClause())->prepare($select, EntityTable::DEFAULT_JOIN_ALIAS);
		
		$r_wheres = [];

		$alias1 = $select->joinRelationshipTable($select->getTableAlias(), 'guid', ['foo1']);
		$rel1 = RelationshipWhereClause::factory([
			'names' => ['foo1'],
			'guid_one' => [1, 2, 3],
		]);
		$r_wheres[] = $rel1->prepare($select, $alias1);

		$alias2 = $select->joinRelationshipTable($select->getTableAlias(), 'guid', ['foo2'], true);
		$rel2 = RelationshipWhereClause::factory([
			'names' => ['foo2'],
			'guid_two' => [4, 5, 6],
		]);
		$r_wheres[] = $rel2->prepare($select, $alias2);
		
		$wheres[] = $select->merge($r_wheres);
		
		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy("{$select->getTableAlias()}.guid", 'asc');

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
				new OrderByClause('e.guid', 'asc'),
			],
		];

		$find = Entities::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithRelationship() {
		$select = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$wheres = [];
		
		$wheres[] = (new AccessWhereClause())->prepare($select, EntityTable::DEFAULT_JOIN_ALIAS);
		
		$select->joinRelationshipTable($select->getTableAlias(), 'guid', null, false, 'inner','r');

		$rel = RelationshipWhereClause::factory([
			'names' => ['foo1'],
			'guid_one' => [1, 2, 3],
		]);
		$wheres[] = $rel->prepare($select, 'r');

		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy("{$select->getTableAlias()}.guid", 'asc');

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
				new OrderByClause('e.guid', 'asc'),
			],
		];

		$find = Entities::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}
	
	public function getRows($limit = 10) {
		$rows = [];
		for ($i = 0; $i < $limit; $i++) {
			$row = (object) [
				'guid' => $i,
				'owner_guid' => rand(100, 999),
				'container_guid' => rand(100, 999),
				'enabled' => 'yes',
				'type' => 'object',
				'subtype' => 'foo',
				'access_id' => ACCESS_PUBLIC,
				'time_created' => time(),
				'time_updated' => null,
				'last_action' => null,
			];
			$rows[] = $row;
		}

		return $rows;
	}
}
