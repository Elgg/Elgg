<?php

namespace Elgg\Database;

use Elgg\Database\Clauses\AnnotationWhereClause;
use Elgg\Database\Clauses\EntityWhereClause;
use Elgg\Database\Clauses\JoinClause;
use Elgg\Database\Clauses\OrderByClause;
use Elgg\Database\Clauses\RelationshipWhereClause;
use Elgg\Database\Clauses\RiverWhereClause;
use Elgg\Exceptions\DataFormatException;
use Elgg\UnitTestCase;

class RiverUnitTest extends UnitTestCase {

	public function buildQuery(QueryBuilder $qb, array $options = []) {
		$where = new RiverWhereClause();
		$where->ids = elgg_extract('ids', $options);
		$where->views = elgg_extract('views', $options);
		$where->action_types = elgg_extract('action_types', $options);
		$where->subject_guids = elgg_extract('subject_guids', $options);
		$where->object_guids = elgg_extract('object_guids', $options);
		$where->target_guids = elgg_extract('target_guids', $options);
		$where->created_after = elgg_extract('created_after', $options);
		$where->created_before = elgg_extract('created_before', $options);

		$qb->addClause($where);

		$ands = [];
		
		$qb->joinEntitiesTable($qb->getTableAlias(), 'subject_guid', 'inner', 'se');
		$subject = new EntityWhereClause();
		$subject->guids = elgg_extract('subject_guids', $options);
		$ands[] = $subject->prepare($qb, 'se');

		$qb->joinEntitiesTable($qb->getTableAlias(), 'object_guid', 'inner', 'oe');
		$object = new EntityWhereClause();
		$object->type_subtype_pairs = elgg_extract('type_subtype_pairs', $options);
		$object->guids = elgg_extract('object_guids', $options);
		$ands[] = $object->prepare($qb, 'oe');

		$target_ors = [];
		$qb->joinEntitiesTable($qb->getTableAlias(), 'target_guid', 'left', 'te');
		$target = new EntityWhereClause();
		$target->guids = elgg_extract('target_guids', $options);
		$target_ors[] = $target->prepare($qb, 'te');
		// Note the LEFT JOIN
		$target_ors[] = $qb->compare('te.guid', 'IS NULL');
		$ands[] = $qb->merge($target_ors, 'OR');

		$qb->andWhere($qb->merge($ands));
		
		return $qb;
	}

	public function testCanExecuteCount() {
		$options = [
			'count' => true,
			'subject_guids' => [1, 2, 3],
			'object_guids' => [4, 5, 6],
			'target_guids' => [7, 8, 9],
		];

		$select = Select::fromTable(RiverTable::TABLE_NAME, RiverTable::DEFAULT_JOIN_ALIAS);
		$select->select("COUNT(DISTINCT {$select->getTableAlias()}.id) AS total");

		$select = $this->buildQuery($select, $options);

		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => [
				(object) [
					'total' => 10,
				]
			]
		]);

		$find = River::find($options);
		$count = River::with($options)->count();

		$this->assertEquals(10, $find);
		$this->assertEquals(10, $count);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteWithIgnoredAccess() {
		$select = Select::fromTable(RiverTable::TABLE_NAME, RiverTable::DEFAULT_JOIN_ALIAS);
		$select->select("COUNT(DISTINCT {$select->getTableAlias()}.id) AS total");

		$select->addClause(new RiverWhereClause());

		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => [
				(object) [
					'total' => 10,
				]
			]
		]);

		elgg_call(ELGG_IGNORE_ACCESS, function() {
			$options = [
				'count' => true,
			];
			
			$find = River::find($options);
			$count = River::with($options)->count();

			$this->assertEquals(10, $find);
			$this->assertEquals(10, $count);
		});
		
		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testThrowsOnBadDataFormat() {
		$this->expectException(DataFormatException::class);
		River::find([
			'count' => true,
			'subject_guids' => 'abc',
		]);
	}

	public function testCanExecuteGet() {
		$options = [
			'limit' => 5,
			'offset' => 5,
			'callback' => false,
			'order_by' => [
				new OrderByClause('rv.id', 'ASC'),
			]
		];
		
		$select = Select::fromTable(RiverTable::TABLE_NAME, RiverTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$select = $this->buildQuery($select, $options);

		$select->setMaxResults(5);
		$select->setFirstResult(5);
		$select->addOrderBy("{$select->getTableAlias()}.id", 'asc');

		$rows = $this->getRows(5);

		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$find = River::find($options);
		$get = River::with($options)->get(5, 5, false);

		$this->assertEquals($rows, $find);
		$this->assertEquals($rows, $get);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteGetWithClauses() {
		$options = [
			'limit' => 5,
			'offset' => 5,
			'callback' => false,
			'order_by' => [
				new OrderByClause('rv.id', 'ASC'),
			],
			'selects' => [
				'max(rv.posted) AS newest',
			],
			'group_by' => [
				'rv.posted',
			],
			'having' => [
				function (QueryBuilder $qb, $main_alias) {
					return $qb->compare("{$main_alias}.posted", 'IS NOT NULL');
				}
			],
			'joins' => [
				new JoinClause(AnnotationsTable::TABLE_NAME, 'n_table', 'rv.annotation_id = n_table.id'),
			],
			'wheres' => [
				function (QueryBuilder $qb, $main_alias) {
					$alias = $qb->joinEntitiesTable($main_alias, 'object_guid', 'object');

					return $qb->compare("{$alias}.access_id", 'IN', [1, 2, 3], ELGG_VALUE_INTEGER);
				}
			]
		];
		
		$select = Select::fromTable(RiverTable::TABLE_NAME, RiverTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		$select->addSelect("max({$select->getTableAlias()}.posted) AS newest");
		$select->groupBy("{$select->getTableAlias()}.posted");
		$select->join($select->getTableAlias(), AnnotationsTable::TABLE_NAME, 'n_table', "{$select->getTableAlias()}.annotation_id = n_table.id");
		$alias = $select->joinEntitiesTable($select->getTableAlias(), 'object_guid', 'object');
		$select->where($select->compare("{$alias}.access_id", 'IN', [1, 2, 3], ELGG_VALUE_INTEGER));
		$select->having($select->compare("{$select->getTableAlias()}.posted", 'IS NOT NULL'));

		$select = $this->buildQuery($select, $options);

		$select->setMaxResults(5);
		$select->setFirstResult(5);
		$select->addOrderBy("{$select->getTableAlias()}.id", 'asc');

		$rows = $this->getRows(5);

		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$find = River::find($options);
		$get = River::with($options)->get(5, 5, false);

		$this->assertEquals($rows, $find);
		$this->assertEquals($rows, $get);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteBatchGet() {
		$options = [
			'limit' => 5,
			'offset' => 5,
			'callback' => false,
			'order_by' => [
				new OrderByClause('rv.id', 'ASC'),
			],
			'batch' => true,
		];
		
		$select = Select::fromTable(RiverTable::TABLE_NAME, RiverTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$select = $this->buildQuery($select, $options);

		$select->setMaxResults(5);
		$select->setFirstResult(5);
		$select->addOrderBy("{$select->getTableAlias()}.id", 'asc');

		$rows = $this->getRows(5);

		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$find = River::find($options);
		$batch = River::with($options)->batch(5, 5, false);

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

		$options = [
			'annotation_calculation' => 'avg',
			'annotation_name_value_pairs' => [
				'name' => $annotation_names,
				'value' => 10,
				'operand' => '>',
			]
		];
		
		$select = Select::fromTable(RiverTable::TABLE_NAME, RiverTable::DEFAULT_JOIN_ALIAS);

		$alias = $select->joinAnnotationTable($select->getTableAlias(), 'annotation_id', null, 'inner', AnnotationsTable::DEFAULT_JOIN_ALIAS);
		$select->select("avg({$alias}.value) AS calculation");

		$select = $this->buildQuery($select, $options);

		$annotation = new AnnotationWhereClause();
		$annotation->names = $annotation_names;
		$annotation->values = 10;
		$annotation->comparison = '>';
		$annotation->value_type = ELGG_VALUE_INTEGER;

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

		$find = River::find($options);
		$calculate = River::with($options)->calculate('avg', $annotation_names, 'annotation');

		$this->assertEquals(10, $find);
		$this->assertEquals(10, $calculate);

		_elgg_services()->db->removeQuerySpec($spec);
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
		River::find($options);
	}

	public function testCanExecuteQueryWithAnnotationNameValuePairs() {
		$options = [
			'callback' => false,
			'annotation_name_value_pairs' => [
				'foo1' => 'bar1',
				'foo2' => 'bar2',
			],
			'order_by' => [
				new OrderByClause('rv.id', 'asc'),
			],
			'limit' => 10,
		];
		
		$select = Select::fromTable(RiverTable::TABLE_NAME, RiverTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");

		$wheres = [];
		
		$select = $this->buildQuery($select, $options);

		$alias1 = $select->joinAnnotationTable($select->getTableAlias(), 'annotation_id', ['foo1']);
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo1'];
		$annotation->values = ['bar1'];
		$wheres[] = $annotation->prepare($select, $alias1);

		$alias2 = $select->joinAnnotationTable($select->getTableAlias(), 'annotation_id', ['foo2']);
		$annotation = new AnnotationWhereClause();
		$annotation->names = ['foo2'];
		$annotation->values = ['bar2'];
		$wheres[] = $annotation->prepare($select, $alias2);

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

		$find = River::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithAnnotationNameValuePairsJoinedByOr() {
		$options = [
			'callback' => false,
			'annotation_name_value_pairs' => [
				'foo1' => 'bar1',
				'foo2' => 'bar2',
			],
			'annotation_name_value_pairs_operator' => 'OR',
			'order_by' => [
				new OrderByClause('rv.id', 'asc'),
			],
			'limit' => 10,
		];
		
		$select = Select::fromTable(RiverTable::TABLE_NAME, RiverTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$wheres = [];
		
		$select = $this->buildQuery($select, $options);

		$alias = $select->joinAnnotationTable($select->getTableAlias(), 'annotation_id');

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

		$select->orderBy("{$select->getTableAlias()}.id", 'asc');

		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$find = River::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithRelationshipPairs() {
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
				new OrderByClause('rv.id', 'asc'),
			],
			'limit' => 10,
		];
		
		$select = Select::fromTable(RiverTable::TABLE_NAME, RiverTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$wheres = [];
		
		$select = $this->buildQuery($select, $options);

		$alias1 = $select->joinRelationshipTable($select->getTableAlias(), 'subject_guid', ['foo1']);
		$rel1 = new RelationshipWhereClause();
		$rel1->names = ['foo1'];
		$rel1->subject_guids = [1, 2, 3];
		$wheres[] = $rel1->prepare($select, $alias1);

		$alias2 = $select->joinRelationshipTable($select->getTableAlias(), 'subject_guid', ['foo2'], true);
		$rel2 = new RelationshipWhereClause();
		$rel2->names = ['foo2'];
		$rel2->object_guids = [4, 5, 6];
		$wheres[] = $rel2->prepare($select, $alias2);

		$select->andWhere($select->expr()->andX()->addMultiple($wheres));

		$select->setMaxResults(10);
		$select->setFirstResult(0);

		$select->orderBy("{$select->getTableAlias()}.id", 'asc');

		$rows = $this->getRows(5);
		$spec = _elgg_services()->db->addQuerySpec([
			'sql' => $select->getSQL(),
			'params' => $select->getParameters(),
			'results' => $rows,
		]);

		$find = River::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function testCanExecuteQueryWithRelationship() {
		$options = [
			'callback' => false,
			'relationship_guid' => [1, 2, 3],
			'relationship' => ['foo1'],
			'inverse_relationship' => false,
			'order_by' => [
				new OrderByClause('rv.id', 'asc'),
			],
			'limit' => 10,
		];
		
		$select = Select::fromTable(RiverTable::TABLE_NAME, RiverTable::DEFAULT_JOIN_ALIAS);
		$select->select("DISTINCT {$select->getTableAlias()}.*");
		
		$wheres = [];
		
		$select = $this->buildQuery($select, $options);

		$select->joinRelationshipTable($select->getTableAlias(), 'subject_guid', null, false, 'inner', 'r');

		$relationship = new RelationshipWhereClause();
		$relationship->names = ['foo1'];
		$relationship->subject_guids = [1, 2, 3];
		$wheres[] = $relationship->prepare($select, 'r');

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

		$find = River::find($options);

		$this->assertEquals($rows, $find);

		_elgg_services()->db->removeQuerySpec($spec);
	}

	public function getRows($limit = 10) {
		$rows = [];
		for ($i = 0; $i < $limit; $i++) {
			$row = (object) [
				'id' => $i,
				'subject_guid' => rand(100, 999),
				'object_guid' => rand(100, 999),
				'target_guid' => rand(100, 999),
				'enabled' => 'yes',
				'type' => 'object',
				'subtype' => 'foo',
				'access_id' => ACCESS_PUBLIC,
				'posted' => time(),
				'view' => 'foo/bar',
				'action_type' => 'foo:bar',
			];
			$rows[] = $row;
		}

		return $rows;
	}
}
