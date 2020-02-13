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
 * @group Annotations
 * @group QueryBuilder
 * @group Repository
 */
class AnnotationRepositoryTest extends UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testCanExecuteCount() {
		$select = Select::fromTable('annotations', 'n_table');
		$select->select('COUNT(DISTINCT n_table.id) AS total');

		$select->addClause(new AnnotationWhereClause(), 'n_table');

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

		$find = Annotations::find($options);
		$count = Annotations::with($options)->count();

		$this->assertEquals(10, $find);
		$this->assertEquals(10, $count);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteCountWithBadDataFormat() {
		$options = [
			'count' => true,
			'guids' => 'abc',
		];

		$find = Annotations::find($options);
		$this->assertEquals(0, $find);
	}

	public function testCanExecuteGet() {
		$select = Select::fromTable('annotations', 'n_table');
		$select->select('DISTINCT n_table.*');

		$select->addClause(new AnnotationWhereClause(), 'n_table');

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

		$find = Annotations::find($options);
		$get = Annotations::with($options)->get(5, 5, false);

		$this->assertEquals($rows, $find);
		$this->assertEquals($rows, $get);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteGetWithClauses() {
		$select = Select::fromTable('annotations', 'n_table');
		$select->select('DISTINCT n_table.*');
		$select->addSelect('max(e.time_created) AS newest');
		$select->groupBy('n_table.entity_guid');
		$select->join('n_table', 'annotations', 'an', ' n_table.entity_guid = an.entity_guid');
		$alias = $select->joinAnnotationTable('n_table', 'entity_guid', 'status');
		$select->where($select->compare("$alias.value", 'IN', ['draft'], ELGG_VALUE_STRING));
		$select->having($select->compare('e.time_updated', 'IS NOT NULL'));

		$select->addClause(new AnnotationWhereClause(), 'n_table');

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
					$alias = $qb->joinAnnotationTable('n_table', 'entity_guid', 'status');

					return $qb->compare("$alias.value", 'IN', ['draft'], ELGG_VALUE_STRING);
				}
			]
		];

		$find = Annotations::find($options);
		$get = Annotations::with($options)->get(5, 5, false);

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

		$find = Annotations::find($options);
		$this->assertEquals(false, $find);
	}

	public function testCanExecuteBatchGet() {
		$select = Select::fromTable('annotations', 'n_table');
		$select->select('DISTINCT n_table.*');

		$select->addClause(new AnnotationWhereClause(), 'n_table');

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

		$select = Select::fromTable('annotations', 'n_table');
		$select->select("min(n_table.value) AS calculation");

		$annotation = new AnnotationWhereClause();
		$annotation->names = $annotation_names;
		$select->addClause($annotation, 'n_table');

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

		$select = Select::fromTable('annotations', 'n_table');

		$alias = $select->joinAnnotationTable('n_table', 'entity_guid', $annotation_calculation_names);
		$select->select("min($alias.value) AS calculation");

		$annotation = new AnnotationWhereClause();
		$annotation->names = $annotation_names;
		$select->addClause($annotation, 'n_table');

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
			'annotation_calculation' => 'min',
			'annotation_names' => $annotation_names,
		];

		$calculate = Annotations::with($options)->calculate('min', $annotation_calculation_names, 'annotation');

		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteAnnotationsCalculationWithoutPropertyType() {

		$annotation_name = 'foo';

		$select = Select::fromTable('annotations', 'n_table');
		$select->select("min(n_table.value) AS calculation");

		$select->addClause(new AnnotationWhereClause(), 'n_table');

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

		$calculate = Annotations::with([])->calculate('min', $annotation_name);

		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteMetadataCalculation() {

		$metadata_names = ['foo'];

		$select = Select::fromTable('annotations', 'n_table');

		$alias = $select->joinMetadataTable('n_table', 'entity_guid', $metadata_names);
		$select->select("avg($alias.value) AS calculation");

		$select->addClause(new AnnotationWhereClause(), 'n_table');

		$select->join('n_table', 'entities', 'e', 'e.guid = n_table.entity_guid');
		$select->addClause(new EntityWhereClause(), 'e');

		$metadata = new MetadataWhereClause();
		$metadata->names = $metadata_names;
		$select->addClause($metadata, $alias);

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
		$this->expectException(InvalidArgumentException::class);
		Annotations::with([])->calculate('invalid', 'status', 'annotation');
	}

	public function testCanExecuteAttributeCalculation() {

		$select = Select::fromTable('annotations', 'n_table');

		$select->select("max(e.guid) AS calculation");

		$select->addClause(new AnnotationWhereClause(), 'n_table');

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

		$calculate = Annotations::with([])->calculate('max', 'guid', 'attribute');

		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testThrowsOnInvalidAttributeCalculation() {
		$this->expectException(InvalidParameterException::class);
		Annotations::with([])->calculate('max', 'invalid', 'attribute');
	}

	public function testCanExecutePrivateSettingCalculation() {

		$private_setting_names = ['foo'];

		$select = Select::fromTable('annotations', 'n_table');

		$alias = $select->joinPrivateSettingsTable('n_table', 'entity_guid', $private_setting_names);
		$select->select("sum($alias.value) AS calculation");

		$select->addClause(new AnnotationWhereClause(), 'n_table');

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

		$calculate = Annotations::with($options)->calculate('sum', $private_setting_names, 'private_setting');

		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testThrowsOnAnnotationsCalculationWithMultipleAndPairs() {

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
		Annotations::find($options);
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
		Annotations::find($options);
	}

	public function testCanExecuteQueryWithAnnotationsNameValuePairs() {

		$select = Select::fromTable('annotations', 'n_table');
		$select->select('DISTINCT n_table.*');

		$wheres = [];
		
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo1'];
		$annotation->values = ['bar1'];
		$annotation->ids = [1, 2];
		$annotation->owner_guids = [7, 8];
		$annotation->entity_guids = [1, 2];
		$wheres[] = $annotation->prepare($select, 'n_table');

		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo2'];
		$annotation->values = ['bar2'];
		$annotation->ids = [1, 2];
		$annotation->owner_guids = [7, 8];
		$annotation->entity_guids = [1, 2];
		$wheres[] = $annotation->prepare($select, 'n_table');

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
			'annotation_name_value_pairs' => [
				'foo1' => 'bar1',
				'foo2' => 'bar2',
			],
			'annotation_ids' => [1, 2],
			'annotation_owner_guids' => [7, 8],
			'order_by' => [
				new OrderByClause('n_table.id', 'asc'),
			],
		];

		$find = Annotations::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithAnnotationSortByCalculation() {

		$select = Select::fromTable('annotations', 'n_table');
		$select->select('DISTINCT n_table.*');

		$wheres = [];
		
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo1'];
		$annotation->values = ['bar1'];
		$annotation->ids = [1, 2];
		$annotation->owner_guids = [7, 8];
		$annotation->entity_guids = [1, 2];
		$annotation->sort_by_calculation = 'avg';
		$wheres[] = $annotation->prepare($select, 'n_table');

		$select->andWhere($select->merge($wheres));

		$select->join('n_table', 'entities', 'e', 'e.guid = n_table.entity_guid');
		$where = new EntityWhereClause();
		$where->guids = [1, 2];
		$where->owner_guids = [3, 4];
		$where->container_guids = [5, 6];
		$select->addClause($where, 'e');

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('annotation_calculation', 'desc');
		$select->addOrderBy('n_table.time_created', 'asc');
		$select->addOrderBy('n_table.id', 'asc');

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

		$select = Select::fromTable('annotations', 'n_table');
		$select->select('DISTINCT n_table.*');

		$wheres = [];
		
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo1'];
		$annotation->values = ['bar1'];
		$wheres[] = $annotation->prepare($select, 'n_table');

		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo2'];
		$annotation->values = ['bar2'];
		$wheres[] = $annotation->prepare($select, 'n_table');

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
			'annotation_name_value_pairs' => [
				'foo1' => 'bar1',
				'foo2' => 'bar2',
			],
			'annotation_name_value_pairs_operator' => 'OR',
			'order_by' => [
				new OrderByClause('n_table.id', 'asc'),
			],
		];

		$find = Annotations::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithMetadataNameValuePairs() {

		$select = Select::fromTable('annotations', 'n_table');
		$select->select('DISTINCT n_table.*');

		$wheres = [];
		
		$select->addClause(new AnnotationWhereClause(), 'n_table');

		$select->joinEntitiesTable('n_table', 'entity_guid', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$alias1 = $select->joinMetadataTable('n_table', 'entity_guid');
		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo1'];
		$metadata->values = ['bar1'];
		$wheres[] = $metadata->prepare($select, $alias1);

		$alias2 = $select->joinMetadataTable('n_table', 'entity_guid');
		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo2'];
		$metadata->values = ['bar2'];
		$wheres[] = $metadata->prepare($select, $alias2);

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
			'metadata_name_value_pairs' => [
				'foo1' => 'bar1',
				'foo2' => 'bar2',
			],
			'order_by' => [
				new OrderByClause('n_table.id', 'asc'),
			],
		];

		$find = Annotations::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithMetadataNameValuePairsJoinedByOr() {

		$select = Select::fromTable('annotations', 'n_table');
		$select->select('DISTINCT n_table.*');

		$wheres = [];
		
		$select->addClause(new AnnotationWhereClause(), 'n_table');

		$select->joinEntitiesTable('n_table', 'entity_guid', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$alias = $select->joinMetadataTable('n_table', 'entity_guid', null);

		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo1'];
		$metadata->values = ['bar1'];
		$wheres[] = $metadata->prepare($select, $alias);

		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo2'];
		$metadata->values = ['bar2'];
		$wheres[] = $metadata->prepare($select, $alias);

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
			'metadata_name_value_pairs' => [
				'foo1' => 'bar1',
				'foo2' => 'bar2',
			],
			'metadata_name_value_pairs_operator' => 'OR',
			'order_by' => [
				new OrderByClause('n_table.id', 'asc'),
			],
		];

		$find = Annotations::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithPrivateSettingsNameValuePairs() {

		$select = Select::fromTable('annotations', 'n_table');
		$select->select('DISTINCT n_table.*');

		$wheres = [];
		
		$select->addClause(new AnnotationWhereClause(), 'n_table');

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

		$find = Annotations::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithPrivateSettingsNameValuePairsJoinedByOr() {

		$select = Select::fromTable('annotations', 'n_table');
		$select->select('DISTINCT n_table.*');

		$wheres = [];
		
		$select->addClause(new AnnotationWhereClause(), 'n_table');

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

		$find = Annotations::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	/**
	 * @group RepositoryPairs
	 */
	public function testCanExecuteQueryWithRelationshipPairs() {

		$select = Select::fromTable('annotations', 'n_table');
		$select->select('DISTINCT n_table.*');

		$wheres = [];
		
		$select->addClause(new AnnotationWhereClause(), 'n_table');

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

		$find = Annotations::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithRelationship() {

		$select = Select::fromTable('annotations', 'n_table');
		$select->select('DISTINCT n_table.*');

		$wheres = [];
		
		$select->addClause(new AnnotationWhereClause(), 'n_table');

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
