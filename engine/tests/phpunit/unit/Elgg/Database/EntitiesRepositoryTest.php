<?php

namespace Elgg\Database;

use Elgg\Database\Clauses\AccessWhereClause;
use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\MetadataWhereClause;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\Clauses\PrivateSettingWhereClause;
use Elgg\Database\Clauses\RelationshipWhereClause;
use Elgg\Exceptions\InvalidParameterException;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\UnitTestCase;

/**
 * @group Entities
 * @group QueryBuilder
 * @group Repository
 */
class EntitiesRepositoryTest extends UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testCanExecuteCount() {
		$select = Select::fromTable('entities', 'e');
		$select->select('COUNT(DISTINCT e.guid) AS total');

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

	public function testCanExecuteCountWithBadDataFormat() {
		$options = [
			'count' => true,
			'guids' => 'abc',
		];

		$find = Entities::find($options);
		$this->assertEquals(0, $find);
	}

	public function testCanExecuteGet() {
		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');

		$select->addClause(new AccessWhereClause());

		$select->setMaxResults(5);
		$select->setFirstResult(5);
		$select->addOrderBy('e.guid', 'asc');

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

	public function testCanExecuteGetWithClauses() {
		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');
		$select->addSelect('max(e.time_created) AS newest');
		$select->groupBy('e.time_created');
		$select->join('e', 'metadata', 'n_table', 'e.guid = n_table.entity_guid');
		$alias = $select->joinMetadataTable('e', 'guid', 'status');
		$select->where($select->compare("$alias.value", 'IN', ['draft'], ELGG_VALUE_STRING));
		$select->having($select->compare('e.time_updated', 'IS NOT NULL'));
		$select->addClause(new AccessWhereClause());

		$select->setMaxResults(5);
		$select->setFirstResult(5);
		$select->addOrderBy('e.guid', 'asc');

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
				function(QueryBuilder $qb) {
				return $qb->compare('e.time_updated', 'IS NOT NULL');
			}
			],
			'joins' => [
				new JoinClause('metadata', 'n_table', 'e.guid = n_table.entity_guid'),
			],
			'wheres' =>[
				function(QueryBuilder $qb) {
					$alias = $qb->joinMetadataTable('e', 'guid', 'status');
					return $qb->compare("$alias.value", 'IN', ['draft'], ELGG_VALUE_STRING);
				}
			]
		];

		$find = Entities::find($options);
		$get = Entities::with($options)->get(5, 5, false);

		$this->assertEquals($rows, $find);
		$this->assertEquals($rows, $get);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteGetWithBadDataFormat() {
		$options = [
			'limit' => 5,
			'offset' => 5,
			'callback' => false,
			'guids' => 'abc',
		];

		$find = Entities::find($options);
		$this->assertEquals(false, $find);
	}

	public function testCanExecuteBatchGet() {
		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');

		$access = new AccessWhereClause();
		$access_where = $access->prepare($select, 'e');
		$select->andWhere($access_where);

		$select->setMaxResults(5);
		$select->setFirstResult(5);
		$select->addOrderBy('e.guid', 'asc');

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

		$select = Select::fromTable('entities', 'e');
		$select->joinMetadataTable('e', 'guid', $metadata_names, 'inner', 'n_table');
		$select->select("min(n_table.value) AS calculation");

		$select->addClause(new AccessWhereClause());

		$metadata = new MetadataWhereClause();
		$metadata->names = $metadata_names;
		$select->addClause($metadata, 'n_table');

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

		$select = Select::fromTable('entities', 'e');
		$select->joinMetadataTable('e', 'guid', $metadata_name, 'inner', 'n_table');
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

		$select = Select::fromTable('entities', 'e');
		$select->joinAnnotationTable('e', 'guid', $annotation_names, 'inner', 'n_table');
		$select->select("avg(n_table.value) AS calculation");

		$select->addClause(new AccessWhereClause());

		$annotation = new AnnotationWhereClause();
		$annotation->names = $annotation_names;
		$select->addClause($annotation, 'n_table');

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
		$this->expectException(InvalidArgumentException::class);
		Entities::with([])->calculate('invalid', 'guid', 'attribute');
	}

	public function testCanExecuteAttributeCalculation() {

		$select = Select::fromTable('entities', 'e');
		$select->select("max(e.guid) AS calculation");

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

		$select = Select::fromTable('entities', 'e');
		$select->select("max(e.guid) AS calculation");

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
		$this->expectException(InvalidParameterException::class);
		Entities::with([])->calculate('max', 'invalid', 'attribute');
	}

	public function testCanExecutePrivateSettingCalculation() {

		$private_setting_names = ['foo'];

		$select = Select::fromTable('entities', 'e');
		$select->joinPrivateSettingsTable('e', 'guid', $private_setting_names, 'inner', 'ps');
		$select->select("sum(ps.value) AS calculation");

		$select->addClause(new AccessWhereClause());

		$private_setting = new PrivateSettingWhereClause();
		$private_setting->names = $private_setting_names;
		$select->addClause($private_setting, 'ps');

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
			'private_setting_names' => $private_setting_names,
		];

		$calculate = Entities::with($options)->calculate('sum', $private_setting_names, 'private_setting');

		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
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

		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');

		$wheres = [];
		
		$select->addClause(new AccessWhereClause());

		$alias1 = $select->joinMetadataTable('e', 'guid', ['foo1']);
		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo1'];
		$metadata->values = ['bar1'];
		$wheres[] = $metadata->prepare($select, $alias1);

		$alias2 = $select->joinMetadataTable('e', 'guid', ['foo2']);
		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo2'];
		$metadata->values = ['bar2'];
		$wheres[] = $metadata->prepare($select, $alias2);

		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('e.guid', 'asc');

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

		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');
		
		$wheres = [];
		
		$select->addClause(new AccessWhereClause());

		$select->joinMetadataTable('e', 'guid', null, 'inner','n_table');

		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo1'];
		$metadata->values = ['bar1'];
		$wheres[] = $metadata->prepare($select, 'n_table');

		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo2'];
		$metadata->values = ['bar2'];
		$wheres[] = $metadata->prepare($select, 'n_table');

		$select->andWhere($select->merge($wheres, 'OR'));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('e.guid', 'asc');

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

		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');

		$wheres = [];
		
		$select->addClause(new AccessWhereClause());

		$alias1 = $select->joinAnnotationTable('e', 'guid', ['foo1']);
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo1'];
		$annotation->values = ['bar1'];
		$wheres[] = $annotation->prepare($select, $alias1);

		$alias2 = $select->joinAnnotationTable('e', 'guid', ['foo2']);
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo2'];
		$annotation->values = ['bar2'];
		$wheres[] = $annotation->prepare($select, $alias2);

		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('e.guid', 'asc');

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

		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');
		
		$wheres = [];
		
		$select->addClause(new AccessWhereClause());

		$select->joinAnnotationTable('e', 'guid', null, 'inner','n_table');

		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo1'];
		$annotation->values = ['bar1'];
		$wheres[] = $annotation->prepare($select, 'n_table');

		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo2'];
		$annotation->values = ['bar2'];
		$wheres[] = $annotation->prepare($select, 'n_table');

		$select->andWhere($select->merge($wheres, 'OR'));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('e.guid', 'asc');

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

	public function testCanExecuteQueryWithPrivateSettingsNameValuePairs() {

		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');

		$wheres = [];
		
		$select->addClause(new AccessWhereClause());

		$alias1 = $select->joinPrivateSettingsTable('e', 'guid', ['foo1']);
		$private_setting = new PrivateSettingWhereClause();
		$private_setting->names = ['foo1'];
		$private_setting->values = ['bar1'];
		$wheres[] = $private_setting->prepare($select, $alias1);

		$alias2 = $select->joinPrivateSettingsTable('e', 'guid', ['foo2']);
		$private_setting = new PrivateSettingWhereClause();
		$private_setting->names = ['foo2'];
		$private_setting->values = ['bar2'];
		$wheres[] = $private_setting->prepare($select, $alias2);

		$select->andWhere($select->expr()->andX()->addMultiple($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('e.guid', 'asc');

		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'callback' => false,
			'private_setting_name_value_pairs' => [
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

	public function testCanExecuteQueryWithPrivateSettingsNameValuePairsJoinedByOr() {

		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');
		
		$wheres = [];
		
		$select->addClause(new AccessWhereClause());

		$select->joinPrivateSettingsTable('e', 'guid', null, 'inner','ps');

		$private_setting = new PrivateSettingWhereClause();
		$private_setting->names = ['foo1'];
		$private_setting->values = ['bar1'];
		$wheres[] = $private_setting->prepare($select, 'ps');

		$private_setting = new PrivateSettingWhereClause();
		$private_setting->names = ['foo2'];
		$private_setting->values = ['bar2'];
		$wheres[] = $private_setting->prepare($select, 'ps');

		$select->andWhere($select->merge($wheres, 'OR'));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('e.guid', 'asc');

		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$options = [
			'callback' => false,
			'private_setting_name_value_pairs' => [
				'foo1' => 'bar1',
				'foo2' => 'bar2',
			],
			'private_setting_name_value_pairs_operator' => 'OR',
			'order_by' => [
				new OrderByClause('e.guid', 'asc'),
			],
		];

		$find = Entities::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	/**
	 * @group RepositoryPairs
	 */
	public function testCanExecuteQueryWithRelationshipPairs() {

		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');
		
		$wheres = [];
		
		$select->addClause(new AccessWhereClause());

		$alias1 = $select->joinRelationshipTable('e', 'guid', ['foo1']);
		$private_setting = new RelationshipWhereClause();
		$private_setting->names = ['foo1'];
		$private_setting->subject_guids = [1, 2, 3];
		$wheres[] = $private_setting->prepare($select, $alias1);

		$alias2 = $select->joinRelationshipTable('e', 'guid', ['foo2'], true);
		$private_setting = new RelationshipWhereClause();
		$private_setting->names = ['foo2'];
		$private_setting->object_guids = [4, 5, 6];
		$wheres[] = $private_setting->prepare($select, $alias2);

		$select->andWhere($select->expr()->andX()->addMultiple($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('e.guid', 'asc');

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

		$select = Select::fromTable('entities', 'e');
		$select->select('DISTINCT e.*');

		$wheres = [];
		
		$select->addClause(new AccessWhereClause());

		$select->joinRelationshipTable('e', 'guid', null, false, 'inner','r');

		$private_setting = new RelationshipWhereClause();
		$private_setting->names = ['foo1'];
		$private_setting->subject_guids = [1, 2, 3];
		$wheres[] = $private_setting->prepare($select, 'r');

		$select->andWhere($select->merge($wheres, 'OR'));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('e.guid', 'asc');

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
