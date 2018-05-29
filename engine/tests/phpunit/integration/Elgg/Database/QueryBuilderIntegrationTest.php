<?php

namespace Elgg\Database;

use Elgg\IntegrationTestCase;

/**
 * @group Current
 * @group QueryBuilder
 */
class QueryBuilderIntegrationTest extends IntegrationTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testCanUseSubqueryInComparisonClause() {

		$object = $this->createObject();
		$object->foo = 'bar';

		$qb = Select::fromTable('entities', 'e');
		$qb->select('e.guid');
		$qb->where($qb->compare('e.subtype', '=', $object->subtype, ELGG_VALUE_STRING));
		$qb->orderBy('e.time_created', 'desc');

		$subqb = $qb->subquery('metadata', 'md');
		$subqb->select(1);
		$subqb->where($qb->merge([
			$qb->compare('md.entity_guid', '=', 'e.guid'),
			$qb->compare('md.name', '=', 'foo', ELGG_VALUE_STRING),
			$qb->compare('md.value', '=', 'bar', ELGG_VALUE_STRING),
		]));

		$qb->where($qb->compare(null, 'EXISTS', $subqb->getSQL()));

		$row = elgg()->db->getDataRow($qb);

		$this->assertEquals($object->guid, $row->guid);
	}

	public function testCanUseSubqueryInComparisonClauseMatchingAColumn() {

		$group = $this->createGroup();
		$object = $this->createObject([
			'container_guid' => $group->guid,
		]);

		$qb = Select::fromTable('entities', 'e');
		$qb->select('e.guid');
		$qb->where($qb->compare('e.subtype', '=', $object->subtype, ELGG_VALUE_STRING));
		$qb->orderBy('e.time_created', 'desc');

		$subqb = $qb->subquery('entities', 'e2');
		$subqb->select('e2.guid');
		$subqb->where($qb->compare('e2.subtype', '=', $group->subtype, ELGG_VALUE_STRING));
		$subqb->orderBy('e2.time_created', 'desc');

		$qb->where($qb->compare('e.container_guid', 'IN', $subqb->getSQL()));

		$row = elgg()->db->getDataRow($qb);

		$this->assertEquals($object->guid, $row->guid);
	}
}