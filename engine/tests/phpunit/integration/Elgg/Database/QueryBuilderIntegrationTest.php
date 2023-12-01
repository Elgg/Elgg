<?php

namespace Elgg\Database;

use Elgg\IntegrationTestCase;

class QueryBuilderIntegrationTest extends IntegrationTestCase {

	public function testCanUseSubqueryInComparisonClause() {

		$object = $this->createObject();
		$object->foo = 'bar';

		$qb = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$qb->select("{$qb->getTableAlias()}.guid");
		$qb->where($qb->compare("{$qb->getTableAlias()}.subtype", '=', $object->subtype, ELGG_VALUE_STRING));
		$qb->orderBy("{$qb->getTableAlias()}.time_created", 'desc');

		$metadata = $qb->subquery(MetadataTable::TABLE_NAME, MetadataTable::DEFAULT_JOIN_ALIAS);
		$metadata->select(1);
		$metadata->where($qb->merge([
			$qb->compare("{$metadata->getTableAlias()}.entity_guid", '=', "{$qb->getTableAlias()}.guid"),
			$qb->compare("{$metadata->getTableAlias()}.name", '=', 'foo', ELGG_VALUE_STRING),
			$qb->compare("{$metadata->getTableAlias()}.value", '=', 'bar', ELGG_VALUE_STRING),
		]));

		$qb->where("EXISTS ({$metadata->getSQL()})");

		$row = elgg()->db->getDataRow($qb);

		$this->assertEquals($object->guid, $row->guid);
	}

	public function testCanUseSubqueryInComparisonClauseMatchingAColumn() {

		$group = $this->createGroup();
		$object = $this->createObject([
			'container_guid' => $group->guid,
		]);

		$qb = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$qb->select("{$qb->getTableAlias()}.guid");
		$qb->where($qb->compare("{$qb->getTableAlias()}.subtype", '=', $object->subtype, ELGG_VALUE_STRING));
		$qb->orderBy("{$qb->getTableAlias()}.time_created", 'desc');

		$subqb = $qb->subquery(EntityTable::TABLE_NAME, 'e2');
		$subqb->select("{$subqb->getTableAlias()}.guid");
		$subqb->where($qb->compare("{$subqb->getTableAlias()}.subtype", '=', $group->subtype, ELGG_VALUE_STRING));
		$subqb->orderBy("{$subqb->getTableAlias()}.time_created", 'desc');

		$qb->where($qb->compare("{$qb->getTableAlias()}.container_guid", 'IN', $subqb->getSQL()));

		$row = elgg()->db->getDataRow($qb);

		$this->assertEquals($object->guid, $row->guid);
	}
}
