<?php

/**
 * Test elgg_get_entities_from_private_settings()
 */
class ElggCoreGetEntitiesFromPrivateSettingsTest extends \ElggCoreGetEntitiesBaseTest {

	public function testElggApiGettersEntitiesFromPrivateSettings() {

		// create some test private settings
		$setting_name = 'test_setting_name_' . rand();
		$setting_value = rand(1000, 9999);
		$setting_name2 = 'test_setting_name_' . rand();
		$setting_value2 = rand(1000, 9999);

		$subtypes = $this->getRandomValidSubtypes(['object'], 1);
		$subtype = $subtypes[0];
		$guids = [];

		// our targets
		$valid = new \ElggObject();
		$valid->subtype = $subtype;
		$valid->save();
		$guids[] = $valid->getGUID();
		$valid->setPrivateSetting($setting_name, $setting_value);
		$valid->setPrivateSetting($setting_name2, $setting_value2);

		$valid2 = new \ElggObject();
		$valid2->subtype = $subtype;
		$valid2->save();
		$guids[] = $valid2->getGUID();
		$valid2->setPrivateSetting($setting_name, $setting_value);
		$valid2->setPrivateSetting($setting_name2, $setting_value2);
		
		// simple test with name
		$options = [
			'private_setting_name' => $setting_name
		];

		$entities = elgg_get_entities_from_private_settings($options);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $guids));
			$value = $entity->getPrivateSetting($setting_name);
			$this->assertEqual($value, $setting_value);
		}

		// simple test with value
		$options = [
			'private_setting_value' => $setting_value
		];

		$entities = elgg_get_entities_from_private_settings($options);

		foreach ($entities as $entity) {
			$this->assertTrue(in_array($entity->getGUID(), $guids));
			$value = $entity->getPrivateSetting($setting_name);
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

}
