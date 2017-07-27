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
		elgg_get_entities_from_attributes(array('types' => array('one', 'two')));
	}

	public function testWithInvalidType() {
		$this->expectException(new \InvalidArgumentException("Invalid type 'test' passed to elgg_get_entities_from_attributes()"));
		elgg_get_entities_from_attributes(array('type' => 'test'));
	}

	public function testWithInvalidPair() {
		$this->expectException(new \InvalidArgumentException("attribute_name_value_pairs must be an array for elgg_get_entities_from_attributes()"));
		elgg_get_entities_from_attributes(array(
			'types' => 'object',
			'attribute_name_value_pairs' => 'invalid',
		));
	}

	public function testGetUserByUsername() {
		// grab a user
		foreach ($this->entities as $e) {
			if (elgg_instanceof($e, 'user')) {
				break;
			}
		}

		$result = elgg_get_entities_from_attributes(array(
			'type' => 'user',
			'attribute_name_value_pairs' => array(
				'name' => 'username',
				'value' => $e->username,
			),
		));
		$this->assertEqual($e->guid, $result[0]->guid);
	}
}
