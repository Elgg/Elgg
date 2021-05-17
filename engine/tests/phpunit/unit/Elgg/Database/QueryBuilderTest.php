<?php

namespace Elgg\Database;

use Elgg\UnitTestCase;

/**
 * @group QueryBuilder
 */
class QueryBuilderTest extends UnitTestCase {

	/**
	 * @var QueryBuilder
	 */
	private $qb;

	public function up() {
		$this->qb = Select::fromTable('foo', 'f');
	}

	public function down() {

	}

	public function testCanCreateSubquery() {
		$subquery = $this->qb->subquery('bar', 'b');

		$expected = new Select($this->qb->getConnection());
		$expected->from('bar', 'b');

		$this->assertEquals($expected, $subquery);
	}

	public function testCanPrefixTableName() {

		$dbprefix = elgg_get_config('dbprefix');

		$this->assertEquals("{$dbprefix}table", $this->qb->prefix('table'));
		$this->assertEquals("{$dbprefix}table", $this->qb->prefix("{$dbprefix}table"));
	}

	public function testSetsTableNameAndAlias() {
		$this->assertEquals('foo', $this->qb->getTableName());
		$this->assertEquals('f', $this->qb->getTableAlias());
	}

	public function testCanSetNamedParameter() {

		$key = $this->qb->param('value', ELGG_VALUE_STRING, ':key');
		$this->assertEquals(':key', $key);
		$this->assertEquals('value', $this->qb->getParameter('key'));
	}

	public function testCanSetUnnamedParameter() {
		$key1 = $this->qb->param(1, ELGG_VALUE_INTEGER);
		$key2 = $this->qb->param([1, 2], ELGG_VALUE_INTEGER);

		$this->assertNotEquals($key1, $key2);
		$this->assertEquals(1, $this->qb->getParameter(ltrim($key1,':')));
		$this->assertEquals([1, 2], $this->qb->getParameter(ltrim($key2,':')));
	}

	public function testCanJoinEntitiesTableWithAlias() {

		$alias = $this->qb->joinEntitiesTable('f', 'entity_guid', 'inner', 'e');
		$this->qb->where($this->qb->compare('e.guid', '=', 1, ELGG_VALUE_GUID));

		$this->assertEquals('e', $alias);

		$expected = Select::fromTable('foo', 'f');
		$expected->join('f', $this->qb->prefix('entities'), 'e', 'e.guid = f.entity_guid')
			->where($this->qb->expr()->eq('e.guid', $expected->param(1, ELGG_VALUE_GUID)));

		$this->assertEquals($expected->getSQL(), $this->qb->getSQL());
		$this->assertEquals($expected->getParameters(), $this->qb->getParameters());
	}

	public function testCanJoinEntitiesTableWithoutAlias() {

		$alias1 = $this->qb->joinEntitiesTable('f', 'entity_guid', 'inner');
		$alias2 = $this->qb->joinEntitiesTable('f', 'entity_guid', 'inner');
		$alias3 = $this->qb->joinEntitiesTable('f', 'entity_guid', 'left');
		$alias4 = $this->qb->joinEntitiesTable('f', 'entity_guid', 'inner', $alias1);
		$this->qb->where($this->qb->compare("$alias3.guid", '=', 1, ELGG_VALUE_GUID));

		$this->assertEquals($alias1, $alias2);
		$this->assertEquals($alias1, $alias4);
		$this->assertNotEquals($alias1, $alias3);

		$expected = Select::fromTable('foo', 'f');
		$expected->join('f', $this->qb->prefix('entities'), $alias1, "$alias1.guid = f.entity_guid")
			->leftJoin('f', $this->qb->prefix('entities'), $alias3, "$alias3.guid = f.entity_guid")
			->where($this->qb->expr()->eq("$alias3.guid", $expected->param(1, ELGG_VALUE_GUID)));

		$this->assertEquals($expected->getSQL(), $this->qb->getSQL());
		$this->assertEquals($expected->getParameters(), $this->qb->getParameters());
	}
	
	public function testCanJoinMetadataTableWithAlias() {

		$alias = $this->qb->joinMetadataTable('f', 'guid', 'metadata_name', 'inner', 'n_table');

		$this->assertEquals('n_table', $alias);

		$expected = Select::fromTable('foo', 'f');
		$on = $expected->merge([
			$expected->compare("n_table.entity_guid", '=', 'f.guid'),
			$expected->compare("n_table.name", '=', 'metadata_name', ELGG_VALUE_STRING),
		]);
		$expected->join('f', $this->qb->prefix('metadata'), 'n_table', $on);

		$this->assertEquals($expected->getSQL(), $this->qb->getSQL());
		$this->assertEquals($expected->getParameters(), $this->qb->getParameters());
	}
	
	public function testCanJoinMetadataTableWithoutName() {

		$alias = $this->qb->joinMetadataTable('f', 'guid', null, 'inner', 'n_table');

		$this->assertEquals('n_table', $alias);

		$expected = Select::fromTable('foo', 'f');
		$on = $expected->merge([
			$expected->compare("n_table.entity_guid", '=', 'f.guid'),
		]);
		$expected->join('f', $this->qb->prefix('metadata'), 'n_table', $on);

		$this->assertEquals($expected->getSQL(), $this->qb->getSQL());
		$this->assertEquals($expected->getParameters(), $this->qb->getParameters());
	}
	
	public function testCanJoinMetadataTableWithoutAlias() {

		$alias1 = $this->qb->joinMetadataTable('f', 'guid', 'metadata_name', 'inner');
		$alias2 = $this->qb->joinMetadataTable('f', 'guid', 'metadata_name', 'inner');
		$alias3 = $this->qb->joinMetadataTable('f', 'guid', 'metadata_name', 'left');
		$alias4 = $this->qb->joinMetadataTable('f', 'guid', 'metadata_name', 'inner', $alias1);

		$this->assertEquals($alias1, $alias2);
		$this->assertEquals($alias1, $alias4);
		$this->assertNotEquals($alias1, $alias3);

		$expected = Select::fromTable('foo', 'f');
		$on = $expected->merge([
			$expected->compare("$alias1.entity_guid", '=', 'f.guid'),
			$expected->compare("$alias1.name", '=', 'metadata_name', ELGG_VALUE_STRING),
		]);
		$expected->join('f', $this->qb->prefix('metadata'), $alias1, $on);

		$on = $expected->merge([
			$expected->compare("$alias3.entity_guid", '=', 'f.guid'),
			$expected->compare("$alias3.name", '=', 'metadata_name', ELGG_VALUE_STRING),
		]);
		$expected->leftJoin('f', $this->qb->prefix('metadata'), $alias3, $on);

		$this->assertEquals($expected->getSQL(), $this->qb->getSQL());
		$this->assertEquals($expected->getParameters(), $this->qb->getParameters());
	}

	public function testCanJoinAnnotationTableWithAlias() {

		$alias = $this->qb->joinAnnotationTable('f', 'guid', 'annotation_name', 'inner', 'n_table');

		$this->assertEquals('n_table', $alias);

		$expected = Select::fromTable('foo', 'f');
		$on = $expected->merge([
			$expected->compare("n_table.entity_guid", '=', 'f.guid'),
			$expected->compare("n_table.name", '=', 'annotation_name', ELGG_VALUE_STRING),
		]);
		$expected->join('f', $this->qb->prefix('annotations'), 'n_table', $on);

		$this->assertEquals($expected->getSQL(), $this->qb->getSQL());
		$this->assertEquals($expected->getParameters(), $this->qb->getParameters());
	}

	public function testCanJoinAnnotationTableWithoutName() {

		$alias = $this->qb->joinAnnotationTable('f', 'guid', null, 'inner', 'n_table');

		$this->assertEquals('n_table', $alias);

		$expected = Select::fromTable('foo', 'f');
		$on = $expected->merge([
			$expected->compare("n_table.entity_guid", '=', 'f.guid'),
		]);
		$expected->join('f', $this->qb->prefix('annotations'), 'n_table', $on);

		$this->assertEquals($expected->getSQL(), $this->qb->getSQL());
		$this->assertEquals($expected->getParameters(), $this->qb->getParameters());
	}

	public function testCanJoinAnnotationTableWithoutAlias() {

		$alias1 = $this->qb->joinAnnotationTable('f', 'guid', 'annotation_name', 'inner');
		$alias2 = $this->qb->joinAnnotationTable('f', 'guid', 'annotation_name', 'inner');
		$alias3 = $this->qb->joinAnnotationTable('f', 'guid', 'annotation_name', 'left');
		$alias4 = $this->qb->joinAnnotationTable('f', 'guid', 'annotation_name', 'inner', $alias1);

		$this->assertEquals($alias1, $alias2);
		$this->assertEquals($alias1, $alias4);
		$this->assertNotEquals($alias1, $alias3);

		$expected = Select::fromTable('foo', 'f');
		$on = $expected->merge([
			$expected->compare("$alias1.entity_guid", '=', 'f.guid'),
			$expected->compare("$alias1.name", '=', 'annotation_name', ELGG_VALUE_STRING),
		]);
		$expected->join('f', $this->qb->prefix('annotations'), $alias1, $on);

		$on = $expected->merge([
			$expected->compare("$alias3.entity_guid", '=', 'f.guid'),
			$expected->compare("$alias3.name", '=', 'annotation_name', ELGG_VALUE_STRING),
		]);
		$expected->leftJoin('f', $this->qb->prefix('annotations'), $alias3, $on);

		$this->assertEquals($expected->getSQL(), $this->qb->getSQL());
		$this->assertEquals($expected->getParameters(), $this->qb->getParameters());
	}

	public function testCanJoinPrivateSettingsTableWithAlias() {

		$alias = $this->qb->joinPrivateSettingsTable('f', 'guid', 'private_setting_name', 'inner', 'ps');

		$this->assertEquals('ps', $alias);

		$expected = Select::fromTable('foo', 'f');
		$on = $expected->merge([
			$expected->compare("ps.entity_guid", '=', 'f.guid'),
			$expected->compare("ps.name", '=', 'private_setting_name', ELGG_VALUE_STRING),
		]);
		$expected->join('f', $this->qb->prefix('private_settings'), 'ps', $on);

		$this->assertEquals($expected->getSQL(), $this->qb->getSQL());
		$this->assertEquals($expected->getParameters(), $this->qb->getParameters());
	}

	public function testCanJoinPrivateSettingsTableWithoutName() {

		$alias = $this->qb->joinPrivateSettingsTable('f', 'guid', null, 'inner', 'ps');

		$this->assertEquals('ps', $alias);

		$expected = Select::fromTable('foo', 'f');
		$on = $expected->merge([
			$expected->compare("ps.entity_guid", '=', 'f.guid'),
		]);
		$expected->join('f', $this->qb->prefix('private_settings'), 'ps', $on);

		$this->assertEquals($expected->getSQL(), $this->qb->getSQL());
		$this->assertEquals($expected->getParameters(), $this->qb->getParameters());
	}

	public function testCanJoinPrivateSettingsTableWithoutAlias() {

		$alias1 = $this->qb->joinPrivateSettingsTable('f', 'guid', 'private_setting_name', 'inner');
		$alias2 = $this->qb->joinPrivateSettingsTable('f', 'guid', 'private_setting_name', 'inner');
		$alias3 = $this->qb->joinPrivateSettingsTable('f', 'guid', 'private_setting_name', 'left');
		$alias4 = $this->qb->joinPrivateSettingsTable('f', 'guid', 'private_setting_name', 'inner', $alias1);

		$this->assertEquals($alias1, $alias2);
		$this->assertEquals($alias1, $alias4);
		$this->assertNotEquals($alias1, $alias3);

		$expected = Select::fromTable('foo', 'f');
		$on = $expected->merge([
			$expected->compare("$alias1.entity_guid", '=', 'f.guid'),
			$expected->compare("$alias1.name", '=', 'private_setting_name', ELGG_VALUE_STRING),
		]);
		$expected->join('f', $this->qb->prefix('private_settings'), $alias1, $on);

		$on = $expected->merge([
			$expected->compare("$alias3.entity_guid", '=', 'f.guid'),
			$expected->compare("$alias3.name", '=', 'private_setting_name', ELGG_VALUE_STRING),
		]);
		$expected->leftJoin('f', $this->qb->prefix('private_settings'), $alias3, $on);

		$this->assertEquals($expected->getSQL(), $this->qb->getSQL());
		$this->assertEquals($expected->getParameters(), $this->qb->getParameters());
	}

	public function testCanJoinRelationshipTableWithAlias() {

		$alias = $this->qb->joinRelationshipTable('f', 'guid', 'relationship_name', false,'inner', 'r');

		$this->assertEquals('r', $alias);

		$expected = Select::fromTable('foo', 'f');
		$on = $expected->merge([
			$expected->compare("r.guid_two", '=', 'f.guid'),
			$expected->compare("r.relationship", '=', 'relationship_name', ELGG_VALUE_STRING),
		]);
		$expected->join('f', $this->qb->prefix('entity_relationships'), 'r', $on);

		$this->assertEquals($expected->getSQL(), $this->qb->getSQL());
		$this->assertEquals($expected->getParameters(), $this->qb->getParameters());
	}

	public function testCanJoinRelationshipTableWithoutName() {

		$alias = $this->qb->joinRelationshipTable('f', 'guid', null, false,'inner', 'r');

		$this->assertEquals('r', $alias);

		$expected = Select::fromTable('foo', 'f');
		$on = $expected->merge([
			$expected->compare("r.guid_two", '=', 'f.guid'),
		]);
		$expected->join('f', $this->qb->prefix('entity_relationships'), 'r', $on);

		$this->assertEquals($expected->getSQL(), $this->qb->getSQL());
		$this->assertEquals($expected->getParameters(), $this->qb->getParameters());
	}

	public function testCanJoinRelationshipTableWithAliasInverse() {

		$alias = $this->qb->joinRelationshipTable('f', 'guid', 'relationship_name', true,'inner', 'r');

		$this->assertEquals('r', $alias);

		$expected = Select::fromTable('foo', 'f');
		$on = $expected->merge([
			$expected->compare("r.guid_one", '=', 'f.guid'),
			$expected->compare("r.relationship", '=', 'relationship_name', ELGG_VALUE_STRING),
		]);
		$expected->join('f', $this->qb->prefix('entity_relationships'), 'r', $on);

		$this->assertEquals($expected->getSQL(), $this->qb->getSQL());
		$this->assertEquals($expected->getParameters(), $this->qb->getParameters());
	}

	public function testCanJoinRelationshipTableWithoutNameInverse() {

		$alias = $this->qb->joinRelationshipTable('f', 'guid', null, true,'inner', 'r');

		$this->assertEquals('r', $alias);

		$expected = Select::fromTable('foo', 'f');
		$on = $expected->merge([
			$expected->compare("r.guid_one", '=', 'f.guid'),
		]);
		$expected->join('f', $this->qb->prefix('entity_relationships'), 'r', $on);

		$this->assertEquals($expected->getSQL(), $this->qb->getSQL());
		$this->assertEquals($expected->getParameters(), $this->qb->getParameters());
	}

	public function testCanJoinRelationshipTableWithoutAlias() {

		$alias1 = $this->qb->joinRelationshipTable('f', 'guid', 'relationship_name', false,'inner');
		$alias2 = $this->qb->joinRelationshipTable('f', 'guid', 'relationship_name', false,'inner');
		$alias3 = $this->qb->joinRelationshipTable('f', 'guid', 'relationship_name', false,'left');
		$alias4 = $this->qb->joinRelationshipTable('f', 'guid', 'relationship_name', false,'inner', $alias1);
		$alias5 = $this->qb->joinRelationshipTable('f', 'guid', 'relationship_name', true,'inner');

		$this->assertEquals($alias1, $alias2);
		$this->assertEquals($alias1, $alias4);
		$this->assertNotEquals($alias1, $alias3);
		$this->assertNotEquals($alias1, $alias5);

		$expected = Select::fromTable('foo', 'f');
		$on = $expected->merge([
			$expected->compare("$alias1.guid_two", '=', 'f.guid'),
			$expected->compare("$alias1.relationship", '=', 'relationship_name', ELGG_VALUE_STRING),
		]);
		$expected->join('f', $this->qb->prefix('entity_relationships'), $alias1, $on);

		$on = $expected->merge([
			$expected->compare("$alias3.guid_two", '=', 'f.guid'),
			$expected->compare("$alias3.relationship", '=', 'relationship_name', ELGG_VALUE_STRING),
		]);
		$expected->leftJoin('f', $this->qb->prefix('entity_relationships'), $alias3, $on);

		$on = $expected->merge([
			$expected->compare("$alias5.guid_one", '=', 'f.guid'),
			$expected->compare("$alias5.relationship", '=', 'relationship_name', ELGG_VALUE_STRING),
		]);
		$expected->join('f', $this->qb->prefix('entity_relationships'), $alias5, $on);

		$this->assertEquals($expected->getSQL(), $this->qb->getSQL());
		$this->assertEquals($expected->getParameters(), $this->qb->getParameters());
	}

	public function testCanCreateSelect() {
		$qb = Select::fromTable('table', 'alias');

		$this->assertInstanceOf(QueryBuilder::class, $qb);
		$this->assertEquals('table', $qb->getTableName());
		$this->assertEquals('alias', $qb->getTableAlias());
	}

	public function testCanCreateInsert() {
		$qb = Insert::intoTable('table', 'alias');

		$this->assertInstanceOf(QueryBuilder::class, $qb);
		$this->assertEquals('table', $qb->getTableName());
	}

	public function testCanCreateUpdate() {
		$qb = Update::table('table', 'alias');

		$this->assertInstanceOf(QueryBuilder::class, $qb);
		$this->assertEquals('table', $qb->getTableName());
		$this->assertEquals('alias', $qb->getTableAlias());
	}

	public function testCanCreateDelete() {
		$qb = Delete::fromTable('table', 'alias');

		$this->assertInstanceOf(QueryBuilder::class, $qb);
		$this->assertEquals('table', $qb->getTableName());
		$this->assertEquals('alias', $qb->getTableAlias());
	}
}