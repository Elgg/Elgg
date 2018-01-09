<?php

namespace Elgg\Integration;

/**
 * Test elgg_get_entities_from_private_settings()
 *
 * @group IntegrationTests
 * @group Entities
 * @group EntityPrivateSettings
 */
class ElggCoreGetEntitiesFromPrivateSettingsTest extends ElggCoreGetEntitiesBaseTest {
	
	/**
	 * @var ElggObject
	 */
	protected $object;
	
	public function up() {
		$this->object = $this->createOne('object');
	}
	
	public function down() {
		$this->object->delete();
		unset($this->object);
	}
	
	public function testElggApiGettersEntitiesFromPrivateSettings() {

		// create some test private settings
		$setting_name = 'test_setting_name_' . rand();
		$setting_value = rand(1000, 9999);
		$setting_name2 = 'test_setting_name_' . rand();
		$setting_value2 = rand(1000, 9999);

		$subtype = $this->getRandomSubtype();

		$guids = [];

		// our targets
		$valid = new \ElggObject();
		$valid->subtype = $subtype;
		$valid->save();
		$guids[] = $valid->getGUID();
		set_private_setting($valid->getGUID(), $setting_name, $setting_value);
		set_private_setting($valid->getGUID(), $setting_name2, $setting_value2);

		$valid2 = new \ElggObject();
		$valid2->subtype = $subtype;
		$valid2->save();
		$guids[] = $valid2->getGUID();
		set_private_setting($valid2->getGUID(), $setting_name, $setting_value);
		set_private_setting($valid2->getGUID(), $setting_name2, $setting_value2);

		// simple test with name
		$options = [
			'private_setting_name' => $setting_name
		];

		$entities = elgg_get_entities_from_private_settings($options);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $guids));
			$value = get_private_setting($entity->getGUID(), $setting_name);
			$this->assertEqual($value, $setting_value);
		}

		// simple test with value
		$options = [
			'private_setting_value' => $setting_value
		];

		$entities = elgg_get_entities_from_private_settings($options);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $guids));
			$value = get_private_setting($entity->getGUID(), $setting_name);
			$this->assertEqual($value, $setting_value);
		}

		// test pairs
		$options = [
			'type' => 'object',
			'subtype' => $subtype,
			'private_setting_name_value_pairs' => [
				[
					'name' => $setting_name,
					'value' => $setting_value
				],
				[
					'name' => $setting_name2,
					'value' => $setting_value2
				]
			]
		];

		$entities = elgg_get_entities_from_private_settings($options);
		$this->assertEqual(2, count($entities));
		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $guids));
		}

		foreach ($guids as $guid) {
			if ($e = get_entity($guid)) {
				$e->delete();
			}
		}
	}
	
	/**
	 * @dataProvider booleanPairsProvider
	 */
	public function testElggGetEntitiesFromBooleanPrivateSettingsPair($value, $query) {
		$this->object->setPrivateSetting('private_setting', $value);
		
		// full pair
		$options = [
			'type' => 'object',
			'subtype' => $this->object->subtype,
			'private_setting_name_value_pairs' => [
				[
					'name' => 'private_setting',
					'value' => $query,
					'operand' => '=',
					'type' => ELGG_VALUE_STRING, // private settings are always cast to string
				],
			],
			'count' => true,
		];
		
		$result = elgg_get_entities($options);
		
		$this->assertEquals(1, $result);
	}
	
	/**
	 * @dataProvider booleanPairsProvider
	 */
	public function testElggGetEntitiesFromBooleanPrivateSettingsShortPair($value, $query) {
		$this->object->setPrivateSetting('private_setting', $value);
		
		// short pair
		$options = [
			'type' => 'object',
			'subtype' => $this->object->subtype,
			'private_setting_name_value_pairs' => [
				[
					'private_setting' => $query,
				],
			],
			'count' => true,
		];
		
		$result = elgg_get_entities($options);
		
		$this->assertEquals(1, $result);
	}
	
	/**
	 * @dataProvider booleanPairsProvider
	 */
	public function testElggGetEntitiesFromBooleanPrivateSettingsNamesValues($value, $query) {
		$this->object->setPrivateSetting('private_setting', $value);
		
		// names and values
		$options = [
			'type' => 'object',
			'subtype' => $this->object->subtype,
			'private_setting_names' => 'private_setting',
			'private_setting_values' => $query,
			'count' => true,
		];
		
		$result = elgg_get_entities($options);
		
		$this->assertEquals(1, $result);
	}
	
	public function booleanPairsProvider() {
		return [
			[true, true],
			[true, 1],
			[true, '1'],
			[1, true],
			[0, false],
		];
	}
}
