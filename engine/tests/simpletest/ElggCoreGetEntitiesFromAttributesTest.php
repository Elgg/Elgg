<?php

/**
 * Test elgg_get_entities_from_attributes()
 */
class ElggCoreGetEntitiesFromAttributesTest extends \ElggCoreGetEntitiesBaseTest {

	public function testWithoutType() {
		$this->expectException(new \InvalidArgumentException('The entity type must be defined for elgg_get_entities_from_attributes()'));
		elgg_get_entities_from_attributes();
	}

	public function testWithMoreThanOneType() {
		$this->expectException(new \InvalidArgumentException('Only one type can be passed to elgg_get_entities_from_attributes()'));
		elgg_get_entities_from_attributes([
			'types' => [
				'one',
				'two'
			]
		]);
	}

	public function testWithInvalidType() {
		$this->expectException(new \InvalidArgumentException("Invalid type 'test' passed to elgg_get_entities_from_attributes()"));
		elgg_get_entities_from_attributes(['type' => 'test']);
	}

	public function testWithInvalidPair() {
		$this->expectException(new \InvalidArgumentException("attribute_name_value_pairs must be an array for elgg_get_entities_from_attributes()"));
		elgg_get_entities_from_attributes([
			'types' => 'user',
			'attribute_name_value_pairs' => 'invalid',
		]);
	}

	public function testGetSqlWithNoAttributePairs() {
		$result = _elgg_get_entity_attribute_where_sql([
			'types' => 'user',
			'attribute_name_value_pairs' => ELGG_ENTITIES_ANY_VALUE,
		]);
		$this->assertIdentical([
			'joins' => [],
			'wheres' => []
		], $result);
	}

	public function testGetSqlWithEmptyPairs() {
		$result = _elgg_get_entity_attribute_where_sql([
			'types' => 'user',
			'attribute_name_value_pairs' => [],
		]);
		$this->assertIdentical([
			'joins' => [],
			'wheres' => []
		], $result);
	}

	public function testGetSqlWithSinglePairAndStringValue() {
		$result = _elgg_get_entity_attribute_where_sql([
			'types' => 'user',
			'attribute_name_value_pairs' => [
				'name' => 'name',
				'value' => 'foo',
			],
			'attribute_name_value_pairs_operator' => 'AND',
		]);
		$CONFIG = _elgg_config();
		$expected = [
			'joins' => ["JOIN {$CONFIG->dbprefix}users_entity type_table ON e.guid = type_table.guid"],
			'wheres' => ["((type_table.name = 'foo'))"],
		];
		$this->assertIdentical($expected, $result);
	}

	public function testGetSqlWithSinglePairAndOperand() {
		$result = _elgg_get_entity_attribute_where_sql([
			'types' => 'user',
			'attribute_name_value_pairs' => [
				'name' => 'name',
				'value' => 'foo',
				'operand' => '<',
			],
			'attribute_name_value_pairs_operator' => 'AND',
		]);
		$CONFIG = _elgg_config();
		$expected = [
			'joins' => ["JOIN {$CONFIG->dbprefix}users_entity type_table ON e.guid = type_table.guid"],
			'wheres' => ["((type_table.name < 'foo'))"],
		];
		$this->assertIdentical($expected, $result);
	}

	public function testGetSqlWithSinglePairAndNumericValue() {
		$result = _elgg_get_entity_attribute_where_sql([
			'types' => 'user',
			'attribute_name_value_pairs' => [
				'name' => 'name',
				'value' => '32',
			],
			'attribute_name_value_pairs_operator' => 'AND',
		]);
		$CONFIG = _elgg_config();
		$expected = [
			'joins' => ["JOIN {$CONFIG->dbprefix}users_entity type_table ON e.guid = type_table.guid"],
			'wheres' => ["((type_table.name = 32))"],
		];
		$this->assertIdentical($expected, $result);
	}

	public function testGetSqlWithSinglePairAndNumericArrayValue() {
		$result = _elgg_get_entity_attribute_where_sql([
			'types' => 'user',
			'attribute_name_value_pairs' => [
				'name' => 'name',
				'value' => [
					1,
					2,
					3
				],
			],
			'attribute_name_value_pairs_operator' => 'AND',
		]);
		$CONFIG = _elgg_config();
		$expected = [
			'joins' => ["JOIN {$CONFIG->dbprefix}users_entity type_table ON e.guid = type_table.guid"],
			'wheres' => ["((type_table.name IN (1, 2, 3)))"],
		];
		$this->assertIdentical($expected, $result);
	}

	public function testGetSqlWithSinglePairAndStringArrayValue() {
		$result = _elgg_get_entity_attribute_where_sql([
			'types' => 'user',
			'attribute_name_value_pairs' => [
				'name' => 'name',
				'value' => [
					'one',
					'two'
				],
			],
			'attribute_name_value_pairs_operator' => 'AND',
		]);
		$CONFIG = _elgg_config();
		$expected = [
			'joins' => ["JOIN {$CONFIG->dbprefix}users_entity type_table ON e.guid = type_table.guid"],
			'wheres' => ["((type_table.name IN ('one', 'two')))"],
		];
		$this->assertIdentical($expected, $result);
	}

	public function testGetSqlWithTwoPairs() {
		$result = _elgg_get_entity_attribute_where_sql([
			'types' => 'user',
			'attribute_name_value_pairs' => [
				[
					'name' => 'username',
					'value' => 'user2'
				],
				[
					'name' => 'email',
					'value' => 'test@example.org'
				],
			],
			'attribute_name_value_pairs_operator' => 'AND',
		]);
		$CONFIG = _elgg_config();
		$expected = [
			'joins' => ["JOIN {$CONFIG->dbprefix}users_entity type_table ON e.guid = type_table.guid"],
			'wheres' => ["((type_table.username = 'user2') AND (type_table.email = 'test@example.org'))"],
		];
		$this->assertIdentical($expected, $result);
	}

	public function testGetSqlWithSinglePairAndCaseInsensitiveStringValue() {
		$result = _elgg_get_entity_attribute_where_sql([
			'types' => 'user',
			'attribute_name_value_pairs' => [
				'name' => 'name',
				'value' => 'foo',
				'case_sensitive' => true,
			],
			'attribute_name_value_pairs_operator' => 'AND',
		]);
		$CONFIG = _elgg_config();
		$expected = [
			'joins' => ["JOIN {$CONFIG->dbprefix}users_entity type_table ON e.guid = type_table.guid"],
			'wheres' => ["((BINARY type_table.name = 'foo'))"],
		];
		$this->assertIdentical($expected, $result);
	}

	public function testGetUserByUsername() {
		// grab a user
		foreach ($this->entities as $e) {
			if (elgg_instanceof($e, 'user')) {
				break;
			}
		}

		$result = elgg_get_entities_from_attributes([
			'type' => 'user',
			'attribute_name_value_pairs' => [
				'name' => 'username',
				'value' => $e->username,
			],
		]);
		$this->assertEqual($e->guid, $result[0]->guid);
	}
}
