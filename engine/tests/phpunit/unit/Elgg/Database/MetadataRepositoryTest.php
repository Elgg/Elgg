<?php

namespace Elgg\Database;

use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\Database\Clauses\EntityWhereClause;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\MetadataWhereClause;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\Clauses\PrivateSettingWhereClause;
use Elgg\Database\Clauses\RelationshipWhereClause;
use Elgg\Exceptions\InvalidParameterException;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\UnitTestCase;

/**
 * @group Metadata
 * @group QueryBuilder
 * @group Repository
 */
class MetadataRepositoryTest extends UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testCanExecuteCount() {
		$select = Select::fromTable('metadata', 'n_table');
		$select->select('COUNT(DISTINCT n_table.id) AS total');

		$select->join('n_table', 'entities', 'e', 'e.guid = n_table.entity_guid');
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

	public function testCanExecuteCountWithBadDataFormat() {
		$options = [
			'count' => true,
			'guids' => 'abc',
		];

		$find = Metadata::find($options);
		$this->assertEquals(0, $find);
	}

	public function testCanExecuteGet() {
		$select = Select::fromTable('metadata', 'n_table');
		$select->select('DISTINCT n_table.*');

		$select->join('n_table', 'entities', 'e', 'e.guid = n_table.entity_guid');
		$select->addClause(new EntityWhereClause(), 'e');

		$select->setMaxResults(5);
		$select->setFirstResult(5);
		$select->addOrderBy('n_table.id', 'asc');

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

	public function testCanExecuteGetWithClauses() {
		$select = Select::fromTable('metadata', 'n_table');
		$select->select('DISTINCT n_table.*');
		$select->addSelect('max(e.time_created) AS newest');
		$select->groupBy('n_table.entity_guid');
		$select->join('n_table', 'annotations', 'an', ' n_table.entity_guid = an.entity_guid');
		$alias = $select->joinMetadataTable('n_table', 'entity_guid', 'status');
		$select->where($select->compare("$alias.value", 'IN', ['draft'], ELGG_VALUE_STRING));
		$select->having($select->compare('e.time_updated', 'IS NOT NULL'));


		$select->join('n_table', 'entities', 'e', 'e.guid = n_table.entity_guid');
		$select->addClause(new EntityWhereClause(), 'e');

		$select->setMaxResults(5);
		$select->setFirstResult(5);
		$select->addOrderBy('n_table.id', 'asc');

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
				new JoinClause('annotations', 'an', 'n_table.entity_guid = an.entity_guid', 'inner'),
			],
			'wheres' => [
				function (QueryBuilder $qb) {
					$alias = $qb->joinMetadataTable('n_table', 'entity_guid', 'status');

					return $qb->compare("$alias.value", 'IN', ['draft'], ELGG_VALUE_STRING);
				}
			]
		];

		$find = Metadata::find($options);
		$get = Metadata::with($options)->get(5, 5, false);

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

		$find = Metadata::find($options);
		$this->assertEquals(false, $find);
	}

	public function testCanExecuteBatchGet() {
		$select = Select::fromTable('metadata', 'n_table');
		$select->select('DISTINCT n_table.*');

		$select->join('n_table', 'entities', 'e', 'e.guid = n_table.entity_guid');
		$select->addClause(new EntityWhereClause(), 'e');

		$select->setMaxResults(5);
		$select->setFirstResult(5);
		$select->addOrderBy('n_table.id', 'asc');

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

		$select = Select::fromTable('metadata', 'n_table');
		$select->select("min(n_table.value) AS calculation");

		$metadata = new MetadataWhereClause();
		$metadata->names = $metadata_names;
		$select->addClause($metadata, 'n_table');

		$select->join('n_table', 'entities', 'e', 'e.guid = n_table.entity_guid');
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

		$select = Select::fromTable('metadata', 'n_table');

		$alias = $select->joinMetadataTable('n_table', 'entity_guid', $metadata_calculation_names);
		$select->select("min($alias.value) AS calculation");

		$metadata = new MetadataWhereClause();
		$metadata->names = $metadata_names;
		$select->addClause($metadata, 'n_table');

		$select->join('n_table', 'entities', 'e', 'e.guid = n_table.entity_guid');
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

		$select = Select::fromTable('metadata', 'n_table');
		$select->select("min(n_table.value) AS calculation");

		$select->join('n_table', 'entities', 'e', 'e.guid = n_table.entity_guid');
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

		$select = Select::fromTable('metadata', 'n_table');

		$alias = $select->joinAnnotationTable('n_table', 'entity_guid', $annotation_names);
		$select->select("avg($alias.value) AS calculation");

		$select->join('n_table', 'entities', 'e', 'e.guid = n_table.entity_guid');
		$select->addClause(new EntityWhereClause(), 'e');

		$annotation = new AnnotationWhereClause();
		$annotation->names = $annotation_names;
		$select->addClause($annotation, $alias);

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
		$this->expectException(InvalidArgumentException::class);
		Metadata::with([])->calculate('invalid', 'status', 'metadata');
	}

	public function testCanExecuteAttributeCalculation() {

		$select = Select::fromTable('metadata', 'n_table');

		$select->select("max(e.guid) AS calculation");

		$select->join('n_table', 'entities', 'e', 'e.guid = n_table.entity_guid');
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
		$this->expectException(InvalidParameterException::class);
		Metadata::with([])->calculate('max', 'invalid', 'attribute');
	}

	public function testCanExecutePrivateSettingCalculation() {

		$private_setting_names = ['foo'];

		$select = Select::fromTable('metadata', 'n_table');
		$alias = $select->joinPrivateSettingsTable('n_table', 'entity_guid', $private_setting_names);
		$select->select("sum($alias.value) AS calculation");

		$select->join('n_table', 'entities', 'e', 'e.guid = n_table.entity_guid');
		$select->addClause(new EntityWhereClause(), 'e');

		$private_setting = new PrivateSettingWhereClause();
		$private_setting->names = $private_setting_names;
		$select->addClause($private_setting, $alias);

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

		$calculate = Metadata::with($options)->calculate('sum', $private_setting_names, 'private_setting');

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
		Metadata::find($options);
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
		Metadata::find($options);
	}

	public function testCanExecuteQueryWithMetadataNameValuePairs() {

		$select = Select::fromTable('metadata', 'n_table');
		$select->select('DISTINCT n_table.*');

		$wheres = [];
		
		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo1'];
		$metadata->values = ['bar1'];
		$metadata->ids = [1, 2];
		$metadata->entity_guids = [1, 2];
		$wheres[] = $metadata->prepare($select, 'n_table');

		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo2'];
		$metadata->values = ['bar2'];
		$metadata->ids = [1, 2];
		$metadata->entity_guids = [1, 2];
		$wheres[] = $metadata->prepare($select, 'n_table');

		$select->andWhere($select->merge($wheres));

		$select->join('n_table', 'entities', 'e', 'e.guid = n_table.entity_guid');
		$where = new EntityWhereClause();
		$where->guids = [1, 2];
		$where->owner_guids = [3, 4];
		$where->container_guids = [5, 6];
		$select->addClause($where, 'e');

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('n_table.id', 'asc');

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

		$select = Select::fromTable('metadata', 'n_table');
		$select->select('DISTINCT n_table.*');
		
		$wheres = [];
		
		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo1'];
		$metadata->values = ['bar1'];
		$wheres[] = $metadata->prepare($select, 'n_table');

		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo2'];
		$metadata->values = ['bar2'];
		$wheres[] = $metadata->prepare($select, 'n_table');

		$select->andWhere($select->merge($wheres, 'OR'));

		$select->join('n_table', 'entities', 'e', 'e.guid = n_table.entity_guid');
		$select->addClause(new EntityWhereClause(), 'e');

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('n_table.id', 'asc');

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

		$select = Select::fromTable('metadata', 'n_table');
		$select->select('DISTINCT n_table.*');
		
		$wheres = [];
		
		$select->joinEntitiesTable('n_table', 'entity_guid', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$alias1 = $select->joinAnnotationTable('n_table', 'entity_guid');
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo1'];
		$annotation->values = ['bar1'];
		$wheres[] = $annotation->prepare($select, $alias1);

		$alias2 = $select->joinAnnotationTable('n_table', 'entity_guid');
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo2'];
		$annotation->values = ['bar2'];
		$wheres[] = $annotation->prepare($select, $alias2);

		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('n_table.id', 'asc');

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

		$select = Select::fromTable('metadata', 'n_table');
		$select->select('DISTINCT n_table.*');
		
		$wheres = [];
		
		$select->joinEntitiesTable('n_table', 'entity_guid', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$alias = $select->joinAnnotationTable('n_table', 'entity_guid', null);

		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo1'];
		$annotation->values = ['bar1'];
		$wheres[] = $annotation->prepare($select, $alias);

		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo2'];
		$annotation->values = ['bar2'];
		$wheres[] = $annotation->prepare($select, $alias);

		$select->andWhere($select->merge($wheres, 'OR'));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('n_table.id', 'asc');

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

	public function testCanExecuteQueryWithPrivateSettingsNameValuePairs() {

		$select = Select::fromTable('metadata', 'n_table');
		$select->select('DISTINCT n_table.*');
		
		$wheres = [];
		
		$select->joinEntitiesTable('n_table', 'entity_guid', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$alias1 = $select->joinPrivateSettingsTable('n_table', 'entity_guid');
		$private_setting = new PrivateSettingWhereClause();
		$private_setting->names = ['foo1'];
		$private_setting->values = ['bar1'];
		$wheres[] = $private_setting->prepare($select, $alias1);

		$alias2 = $select->joinPrivateSettingsTable('n_table', 'entity_guid');
		$private_setting = new PrivateSettingWhereClause();
		$private_setting->names = ['foo2'];
		$private_setting->values = ['bar2'];
		$wheres[] = $private_setting->prepare($select, $alias2);

		$select->andWhere($select->expr()->andX()->addMultiple($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('n_table.id', 'asc');

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
				new OrderByClause('n_table.id', 'asc'),
			],
		];

		$find = Metadata::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithPrivateSettingsNameValuePairsJoinedByOr() {

		$select = Select::fromTable('metadata', 'n_table');
		$select->select('DISTINCT n_table.*');
		
		$wheres = [];
		
		$select->joinEntitiesTable('n_table', 'entity_guid', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$alias = $select->joinPrivateSettingsTable('n_table', 'entity_guid', null, 'inner');

		$private_setting = new PrivateSettingWhereClause();
		$private_setting->names = ['foo1'];
		$private_setting->values = ['bar1'];
		$wheres[] = $private_setting->prepare($select, $alias);

		$private_setting = new PrivateSettingWhereClause();
		$private_setting->names = ['foo2'];
		$private_setting->values = ['bar2'];
		$wheres[] = $private_setting->prepare($select, $alias);

		$select->andWhere($select->merge($wheres, 'OR'));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('n_table.id', 'asc');

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
				new OrderByClause('n_table.id', 'asc'),
			],
		];

		$find = Metadata::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	/**
	 * @group RepositoryPairs
	 */
	public function testCanExecuteQueryWithRelationshipPairs() {

		$select = Select::fromTable('metadata', 'n_table');
		$select->select('DISTINCT n_table.*');
		
		$wheres = [];
		
		$select->joinEntitiesTable('n_table', 'entity_guid', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$alias1 = $select->joinRelationshipTable('n_table', 'entity_guid', null);
		$private_setting = new RelationshipWhereClause();
		$private_setting->names = ['foo1'];
		$private_setting->subject_guids = [1, 2, 3];
		$wheres[] = $private_setting->prepare($select, $alias1);

		$alias2 = $select->joinRelationshipTable('n_table', 'entity_guid', null, true);
		$private_setting = new RelationshipWhereClause();
		$private_setting->names = ['foo2'];
		$private_setting->object_guids = [4, 5, 6];
		$wheres[] = $private_setting->prepare($select, $alias2);

		$select->andWhere($select->expr()->andX()->addMultiple($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('n_table.id', 'asc');

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

		$select = Select::fromTable('metadata', 'n_table');
		$select->select('DISTINCT n_table.*');
		
		$wheres = [];
		
		$select->joinEntitiesTable('n_table', 'entity_guid', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$alias = $select->joinRelationshipTable('n_table', 'entity_guid', ['foo1'], false);

		$private_setting = new RelationshipWhereClause();
		$private_setting->names = ['foo1'];
		$private_setting->subject_guids = [1, 2, 3];
		$wheres[] = $private_setting->prepare($select, $alias);

		$select->andWhere($select->merge($wheres, 'OR'));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('n_table.id', 'asc');

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
