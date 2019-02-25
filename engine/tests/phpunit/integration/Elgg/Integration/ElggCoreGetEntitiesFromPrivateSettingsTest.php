<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;

/**
 * Test elgg_get_entities_from_private_settings()
 *
 * @group IntegrationTests
 * @group Entities
 * @group EntityPrivateSettings
 */
class ElggCoreGetEntitiesFromPrivateSettingsTest extends IntegrationTestCase {

	public function up() {

	}

	public function down() {

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
		$valid->access_id = ACCESS_PUBLIC;
		$valid->save();
		$guids[] = $valid->guid;

		$this->assertTrue($valid->setPrivateSetting($setting_name, $setting_value));
		$this->assertTrue($valid->setPrivateSetting($setting_name2, $setting_value2));

		$settings = _elgg_services()->privateSettings->getAllForEntity($valid);
		$this->assertEquals($setting_value, $settings[$setting_name]);
		$this->assertEquals($setting_value2, $settings[$setting_name2]);

		$valid2 = new \ElggObject();
		$valid2->subtype = $subtype;
		$valid2->access_id = ACCESS_PUBLIC;
		$valid2->save();
		$guids[] = $valid2->guid;

		$this->assertTrue($valid2->setPrivateSetting($setting_name, $setting_value));
		$this->assertTrue($valid2->setPrivateSetting($setting_name2, $setting_value2));

		$settings = _elgg_services()->privateSettings->getAllForEntity($valid2);
		$this->assertEquals($setting_value, $settings[$setting_name]);
		$this->assertEquals($setting_value2, $settings[$setting_name2]);

		// simple test with name
		$options = [
			'private_setting_name' => $setting_name
		];

		$entities = elgg_get_entities_from_private_settings($options);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->guid, $guids));
			$value = $entity->getPrivateSetting($setting_name);
			$this->assertEquals($setting_value, $value);
		}

		// simple test with value
		$options = [
			'private_setting_value' => $setting_value
		];

		$entities = elgg_get_entities_from_private_settings($options);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->guid, $guids));
			$value = $entity->getPrivateSetting($setting_name);
			$this->assertEquals($setting_value, $value);
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
		$this->assertEquals(2, count($entities));
		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->guid, $guids));
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
	public function testElggGetEntitiesFromBooleanPrivateSettings($value, $query, $type) {

		$object = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
		]);

		$this->assertTrue($object->setPrivateSetting('private_setting', $value));

		if (is_bool($value)) {
			$value = (int) $value;
		}

		$this->assertEquals((string) $value, $object->getPrivateSetting('private_setting'));

		$options = [
			'type' => 'object',
			'subtype' => $object->subtype,
			'private_setting_name_value_pairs' => [
				[
					'name' => 'private_setting',
					'value' => $query,
					'operand' => '=',
					'type' => $type,
				]
			],
			'count' => true,
		];

		$result = elgg_get_entities($options);

		$this->assertEquals(1, $result);

		$object->delete();
	}

	public function booleanPairsProvider() {
		return [
			[true, true, null],
			[true, 1, null],
			[true, '1', ELGG_VALUE_INTEGER],
			[false, false, null],
			[false, 0, null],
			[false, '0', ELGG_VALUE_INTEGER],
			[1, true, null],
			[0, false, null],
		];
	}

	public function testCanGetAndRemoveAllOnEntity() {

		$entity = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
		]);

		$entity->setPrivateSetting('setting1', 'value1');
		$entity->setPrivateSetting('setting2', 'value2');

		$settings = $entity->getAllPrivateSettings();
		$this->assertEquals(2, count($settings));
		$this->assertEquals('value1', $settings['setting1']);
		$this->assertEquals('value2', $settings['setting2']);

		$entity->removeAllPrivateSettings();

		$this->assertNull(_elgg_services()->dataCache->private_settings->load($entity->guid));

		$this->assertEmpty($entity->getAllPrivateSettings());

		$entity->delete();
	}

	public function testCanSetAndRemovePrivateSettingOnEntity() {

		$entity = $this->createObject([
			'access_id' => ACCESS_PUBLIC,
		]);

		$entity->setPrivateSetting('setting1', 'value1');
		$entity->setPrivateSetting('setting2', 'value2');

		$this->assertEquals('value1', $entity->getPrivateSetting('setting1'));
		$this->assertEquals('value2', $entity->getPrivateSetting('setting2'));

		$settings = $entity->getAllPrivateSettings();

		$this->assertEquals($settings, _elgg_services()->dataCache->private_settings->load($entity->guid));

		$this->assertEquals(2, count($settings));
		$this->assertEquals('value1', $settings['setting1']);
		$this->assertEquals('value2', $settings['setting2']);

		$this->assertEquals('value1', $entity->getPrivateSetting('setting1'));
		$this->assertEquals('value2', $entity->getPrivateSetting('setting2'));

		$entity->removePrivateSetting('setting2');

		$this->assertNull(_elgg_services()->dataCache->private_settings->load($entity->guid));
		$this->assertNull(_elgg_services()->dataCache->entities->load($entity->guid));

		$settings = $entity->getAllPrivateSettings();
		$this->assertEquals(1, count($settings));
		$this->assertEquals('value1', $settings['setting1']);

		$entity->delete();
	}
}
