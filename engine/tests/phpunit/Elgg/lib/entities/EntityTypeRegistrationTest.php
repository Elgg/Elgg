<?php

namespace Elgg\lib\entities;

/**
 * @group Entities
 */
class EntityTypeRegistrationTest extends \Elgg\TestCase {

	public function setUp() {
		$mocks = new \Elgg\Tests\EntityMocks($this);
		unset(_elgg_services()->entityTypes); // previous state is floating over
		_elgg_services()->setValue('subtypeTable', $mocks->getSubtypeTableMock());
	}

	/**
	 * @dataProvider typeSubtypePairsProvider
	 */
	public function testCanRegisterEntityType($type, $subtype = null) {

		$this->assertFalse(is_registered_entity_type($type, $subtype));

		$this->assertTrue(elgg_register_entity_type($type, $subtype));

		$this->assertTrue(is_registered_entity_type($type, $subtype));

		if ($subtype) {
			$this->assertTrue(in_array($subtype, get_registered_entity_types($type)));
		} else {
			$this->assertTrue(array_key_exists($type, get_registered_entity_types()));
		}

		$this->assertTrue(elgg_unregister_entity_type($type, $subtype));

		$this->assertFalse(is_registered_entity_type($type, $subtype));

	}

	public function typeSubtypePairsProvider() {
		return [
			['object', null],
			['group', null],
			['user', null],
			['object', 'test_object_subtype'],
			['group', 'test_group_subtype'],
			['user', 'test_user_subtype'],
		];
	}

}