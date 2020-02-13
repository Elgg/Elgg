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
 * @group Relationships
 * @group QueryBuilder
 * @group Repository
 */
class RelationshipsRepositoryTest extends UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testCanExecuteCount() {
		$select = Select::fromTable('entity_relationships', 'er');
		$select->select('COUNT(DISTINCT er.id) AS total');

		$select->addClause(new RelationshipWhereClause(), 'er');

		$select->joinEntitiesTable('er', 'guid_one', 'inner', 'e');
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

	public function testCanExecuteCountWithBadDataFormat() {
		$options = [
			'count' => true,
			'guids' => 'abc',
		];

		$find = Relationships::find($options);
		$this->assertEquals(0, $find);
	}

	public function testCanExecuteGet() {
		$select = Select::fromTable('entity_relationships', 'er');
		$select->select('DISTINCT er.*');

		$select->addClause(new RelationshipWhereClause(), 'er');

		$select->joinEntitiesTable('er', 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$select->setMaxResults(5);
		$select->setFirstResult(5);
		$select->addOrderBy('er.id', 'asc');

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

	public function testCanExecuteGetWithClauses() {
		$select = Select::fromTable('entity_relationships', 'er');
		$select->select('DISTINCT er.*');
		$select->addSelect('max(e.time_created) AS newest');
		$select->groupBy('er.guid_one');
		$select->join('er', 'metadata', 'n_table', 'er.guid_one = n_table.entity_guid');
		$alias = $select->joinMetadataTable('e', 'guid', 'status');
		$select->where($select->compare("$alias.value", 'IN', ['draft'], ELGG_VALUE_STRING));
		$select->having($select->compare('e.time_updated', 'IS NOT NULL'));
		$select->joinEntitiesTable('er', 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');
		$select->setMaxResults(5);
		$select->setFirstResult(5);
		
		$select->addOrderBy('er.id', 'asc');

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
				new JoinClause('metadata', 'n_table', 'er.guid_one = n_table.entity_guid'),
			],
			'wheres' => [
				function(QueryBuilder $qb) {
					$alias = $qb->joinMetadataTable('e', 'guid', 'status');
					return $qb->compare("$alias.value", 'IN', ['draft'], ELGG_VALUE_STRING);
				},
			]
		];
		
		$find = Relationships::find($options);
		$get = Relationships::with($options)->get(5, 5, false);
		
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

		$find = Relationships::find($options);
		$this->assertEquals(false, $find);
	}

	public function testCanExecuteBatchGet() {
		$select = Select::fromTable('entity_relationships', 'er');
		$select->select('DISTINCT er.*');

		$select->joinEntitiesTable('er', 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$select->setMaxResults(5);
		$select->setFirstResult(5);
		$select->addOrderBy('er.id', 'asc');

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

		$select = Select::fromTable('entity_relationships', 'er');
		$select->select("min(n_table.value) AS calculation");
		
		$select->joinEntitiesTable('er', 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');
		
		$select->joinAnnotationTable('er', 'guid_one', null, 'inner', 'an');
		
		$annotation = new AnnotationWhereClause();
		$annotation->names = $annotation_names;
		$select->addClause($annotation, 'an');
				
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

		$select = Select::fromTable('entity_relationships', 'er');

		$alias = $select->joinMetadataTable('er', 'guid_one', $metadata_names);
		$select->select("avg($alias.value) AS calculation");

		$select->joinEntitiesTable('er', 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$select->joinMetadataTable('er', 'guid_one', null, 'inner', 'md');
		
		$metadata = new MetadataWhereClause();
		$metadata->names = $metadata_names;
		$select->addClause($metadata, 'md');

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
		$this->expectException(InvalidArgumentException::class);
		Relationships::with([])->calculate('invalid', 'status', 'annotation');
	}

	public function testCanExecuteAttributeCalculation() {

		$select = Select::fromTable('entity_relationships', 'er');

		$select->select("max(e.guid) AS calculation");

		$select->joinEntitiesTable('er', 'guid_one', 'inner', 'e');
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
		$this->expectException(InvalidParameterException::class);
		Relationships::with([])->calculate('max', 'invalid', 'attribute');
	}

	public function testCanExecutePrivateSettingCalculation() {

		$private_setting_names = ['foo'];

		$select = Select::fromTable('entity_relationships', 'er');

		$alias = $select->joinPrivateSettingsTable('er', 'guid_one', $private_setting_names);
		$select->select("sum($alias.value) AS calculation");

		$select->joinEntitiesTable('er', 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$select->joinPrivateSettingsTable('er', 'guid_one', null, 'inner', 'ps');
		
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

		$calculate = Relationships::with($options)->calculate('sum', $private_setting_names, 'private_setting');

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
		Relationships::find($options);
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
		Relationships::find($options);
	}

	public function testCanExecuteQueryWithAnnotationsNameValuePairs() {

		$select = Select::fromTable('entity_relationships', 'er');
		$select->select('DISTINCT er.*');

		$wheres = [];
		
		$select->joinEntitiesTable('er', 'guid_one', 'inner', 'e');
		$where = new EntityWhereClause();
		$where->guids = [1, 2];
		$where->owner_guids = [3, 4];
		$where->container_guids = [5, 6];
		$select->addClause($where, 'e');
		
		$alias = $select->joinAnnotationTable('er', 'guid_one', ['foo1']);
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo1'];
		$annotation->values = ['bar1'];
		$annotation->ids = [1, 2];
		$annotation->owner_guids = [7, 8];
		$annotation->entity_guids = [1, 2];
		$wheres[] = $annotation->prepare($select, $alias);

		$alias = $select->joinAnnotationTable('er', 'guid_one', ['foo2']);
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo2'];
		$annotation->values = ['bar2'];
		$annotation->ids = [1, 2];
		$annotation->owner_guids = [7, 8];
		$annotation->entity_guids = [1, 2];
		$wheres[] = $annotation->prepare($select, $alias);

		$select->andWhere($select->merge($wheres));
		
		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('er.id', 'asc');
		
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

		$select = Select::fromTable('entity_relationships', 'er');
		$select->select('DISTINCT er.*');
		
		$wheres = [];
		
		$select->joinEntitiesTable('er', 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');
		
		$alias = $select->joinAnnotationTable('er', 'guid_one', null, 'inner', 'an');
		
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

		$select->orderBy('er.id', 'asc');

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

		$select = Select::fromTable('entity_relationships', 'er');
		$select->select('DISTINCT er.*');
		
		$wheres = [];
		
		$select->joinEntitiesTable('er', 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$alias1 = $select->joinMetadataTable('er', 'guid_one', ['foo1']);
		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo1'];
		$metadata->values = ['bar1'];
		$wheres[] = $metadata->prepare($select, $alias1);

		$alias2 = $select->joinMetadataTable('er', 'guid_one', ['foo2']);
		$metadata = new MetadataWhereClause();
		$metadata->names = ['foo2'];
		$metadata->values = ['bar2'];
		$wheres[] = $metadata->prepare($select, $alias2);

		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('er.id', 'asc');

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

		$select = Select::fromTable('entity_relationships', 'er');
		$select->select('DISTINCT er.*');
		
		$wheres = [];
		
		$select->joinEntitiesTable('er', 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$alias = $select->joinMetadataTable('er', 'guid_one', null, 'inner', 'md');
		
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

		$select->orderBy('er.id', 'asc');
		
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

	public function testCanExecuteQueryWithPrivateSettingsNameValuePairs() {

		$select = Select::fromTable('entity_relationships', 'er');
		$select->select('DISTINCT er.*');
		
		$wheres = [];
		
		$select->joinEntitiesTable('er', 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$alias1 = $select->joinPrivateSettingsTable('er', 'guid_one', ['foo1']);
		$private_setting = new PrivateSettingWhereClause();
		$private_setting->names = ['foo1'];
		$private_setting->values = ['bar1'];
		$wheres[] = $private_setting->prepare($select, $alias1);

		$alias2 = $select->joinPrivateSettingsTable('er', 'guid_one', ['foo2']);
		$private_setting = new PrivateSettingWhereClause();
		$private_setting->names = ['foo2'];
		$private_setting->values = ['bar2'];
		$wheres[] = $private_setting->prepare($select, $alias2);

		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('er.id', 'asc');

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
				new OrderByClause('er.id', 'asc'),
			],
		];

		$find = Relationships::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithPrivateSettingsNameValuePairsJoinedByOr() {

		$select = Select::fromTable('entity_relationships', 'er');
		$select->select('DISTINCT er.*');
		
		$wheres = [];
		
		$select->joinEntitiesTable('er', 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$alias = $select->joinPrivateSettingsTable('er', 'guid_one', null, 'inner', 'ps');

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

		$select->orderBy('er.id', 'asc');

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
				new OrderByClause('er.id', 'asc'),
			],
		];

		$find = Relationships::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	/**
	 * @group RepositoryPairs
	 */
	public function testCanExecuteQueryWithRelationshipPairs() {

		$select = Select::fromTable('entity_relationships', 'er');
		$select->select('DISTINCT er.*');
		
		$wheres = [];
		
		$select->joinEntitiesTable('er', 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$relationship = new RelationshipWhereClause();
		$relationship->names = ['foo1'];
		$relationship->subject_guids = [1, 2, 3];
		$wheres[] = $relationship->prepare($select, 'er');

		$relationship = new RelationshipWhereClause();
		$relationship->names = ['foo2'];
		$relationship->object_guids = [4, 5, 6];
		$relationship->inverse = true;
		$wheres[] = $relationship->prepare($select, 'er');

		$select->andWhere($select->merge($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('er.id', 'asc');

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

		$select = Select::fromTable('entity_relationships', 'er');
		$select->select('DISTINCT er.*');
		
		$wheres = [];
		
		$select->joinEntitiesTable('er', 'guid_one', 'inner', 'e');
		$select->addClause(new EntityWhereClause(), 'e');

		$relationship = new RelationshipWhereClause();
		$relationship->names = ['foo1'];
		$relationship->subject_guids = [1, 2, 3];
		$wheres[] = $relationship->prepare($select, 'er');

		$select->andWhere($select->merge($wheres, 'OR'));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy('er.id', 'asc');

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
