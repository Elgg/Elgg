<?php

/**
 * Test configuration for site and application (datalist)
 */
class ElggCoreConfigTest extends \ElggCoreUnitTest {

	public function testSetConfigWithTooLongName() {
		_elgg_services()->logger->disable();

		$name = '';
		for ($i = 1; $i <= 256; $i++) {
			$name .= 'a';
		}
		$this->assertFalse(set_config($name, 'foo'));

		_elgg_services()->logger->enable();
	}

	public function testSetConfigWithNewName() {
		global $CONFIG;
		$name = 'foo' . rand(0, 1000);
		$value = 'test';
		$this->assertTrue(set_config($name, $value, 22));
		$this->assertTrue(!isset($CONFIG->$name));
		$this->assertEqual($value, get_config($name, 22));
		$this->assertTrue(unset_config($name, 22));
	}

	public function testSetConfigWithUsedName() {
		$name = 'foo' . rand(0, 1000);
		$value = 'test';
		$this->assertTrue(set_config($name, 'not test', 22));
		$this->assertTrue(set_config($name, $value, 22));
		$this->assertEqual($value, get_config($name, 22));
		$this->assertTrue(unset_config($name, 22));
	}

	public function testSetConfigWithObject() {
		$name = 'foo' . rand(0, 1000);
		$value = new \stdClass();
		$value->test = true;
		$this->assertTrue(set_config($name, $value, 22));
		$this->assertIdentical($value, get_config($name, 22));
		$this->assertTrue(unset_config($name, 22));
	}

	public function testSetConfigWithNonexistentName() {
		$name = 'foo' . rand(0, 1000);
		$this->assertIdentical(null, get_config($name, 22));
	}

	public function testSetConfigWithCurrentSite() {
		global $CONFIG;
		$name = 'foo' . rand(0, 1000);
		$value = 99;
		$this->assertTrue(set_config($name, $value));
		$this->assertIdentical($value, $CONFIG->$name);
		$this->assertIdentical($value, get_config($name, elgg_get_site_entity()->guid));
		$this->assertTrue(unset_config($name));
	}

	public function testGetConfigAlreadyLoadedForCurrentSite() {
		global $CONFIG;
		$CONFIG->foo_unit_test = 35;
		$this->assertIdentical(35, get_config('foo_unit_test'));
		unset($CONFIG->foo_unit_test);
	}

	public function testGetConfigAlreadyLoadedForNotCurrentSite() {
		global $CONFIG;
		$CONFIG->foo_unit_test = 35;
		$this->assertIdentical(null, get_config('foo_unit_test', 34));
		unset($CONFIG->foo_unit_test);
	}

	public function testUnsetConfigWithNonexistentName() {
		$this->assertTrue(unset_config('does_not_exist'));
	}

	public function testUnsetConfigOnNotCurrentSite() {
		global $CONFIG;
		$CONFIG->foo_unit_test = 35;
		$this->assertIdentical(true, unset_config('foo_unit_test', 99));
		$this->assertIdentical(35, $CONFIG->foo_unit_test);
		unset($CONFIG->foo_unit_test);
	}

	public function testUnsetConfigClearsGlobalForCurrentSite() {
		global $CONFIG;
		$CONFIG->foo_unit_test = 35;
		$this->assertIdentical(true, unset_config('foo_unit_test'));
		$this->assertTrue(!isset($CONFIG->foo_unit_test));
	}

	public function testDatalistSetWithTooLongName() {
		_elgg_services()->logger->disable();

		$name = '';
		for ($i = 1; $i <= 256; $i++) {
			$name .= 'a';
		}
		$this->assertFalse(datalist_set($name, 'foo'));

		_elgg_services()->logger->enable();
	}

	public function testDatalistSetNewName() {
		global $CONFIG;
		$name = 'foo' . rand(0, 1000);
		$value = 'test';
		$this->assertTrue(datalist_set($name, $value));
		$this->assertEqual($value, datalist_get($name));
		delete_data("DELETE FROM {$CONFIG->dbprefix}datalists WHERE name = '$name'");
	}

	public function testDatalistSetWithUsedName() {
		global $CONFIG;
		$name = 'foo' . rand(0, 1000);
		$value = 'test';
		$this->assertTrue(datalist_set($name, 'not test'));
		$this->assertTrue(datalist_set($name, $value));
		$this->assertEqual($value, datalist_get($name));
		delete_data("DELETE FROM {$CONFIG->dbprefix}datalists WHERE name = '$name'");
	}

	public function testDatalistGetNonExistentName() {
		$this->assertIdentical(null, datalist_get('imaginary value'));
	}

	public function testElggSaveConfigWithArrayForDatalist() {
		$this->assertFalse(elgg_save_config('testing', array('1'), null));
	}

	public function testElggSaveConfigForDatalist() {
		global $CONFIG;
		$name = 'foo' . rand(0, 1000);
		$value = 'test';
		$this->assertTrue(elgg_save_config($name, $value, null));
		$this->assertIdentical($value, datalist_get($name));
		$this->assertIdentical($value, $CONFIG->$name);
		delete_data("DELETE FROM {$CONFIG->dbprefix}datalists WHERE name = '$name'");
		unset($CONFIG->$name);
	}

	public function testElggSaveConfigForCurrentSiteConfig() {
		global $CONFIG;
		$name = 'foo' . rand(0, 1000);
		$value = 'test';
		$this->assertTrue(elgg_save_config($name, $value));
		$this->assertIdentical($value, get_config($name));
		$this->assertIdentical($value, $CONFIG->$name);
		$this->assertTrue(unset_config($name));
	}

	public function testElggSaveConfigForNonCurrentSiteConfig() {
		global $CONFIG;
		$name = 'foo' . rand(0, 1000);
		$value = 'test';
		$this->assertTrue(elgg_save_config($name, $value, 17));
		$this->assertIdentical($value, get_config($name, 17));
		$this->assertTrue(!isset($CONFIG->$name));
		$this->assertTrue(unset_config($name, 17));
	}

	public function testElggGetConfigNonCurrentSiteConfig() {
		$name = 'foo' . rand(0, 1000);
		$value = 'test';
		$this->assertTrue(elgg_save_config($name, $value, 17));
		$this->assertIdentical($value, elgg_get_config($name, 17));
		$this->assertTrue(unset_config($name, 17));		
	}

	public function testFeatureSetIsSane() {
		$features = _elgg_get_known_features();
		$this->assertTrue($features === array_unique($features));

		foreach ($features as $feature) {
			$matched = (bool)preg_match('~^(\\d+\\.\\d+)\\:(.+)\\z~', $feature, $m);
			$this->assertTrue($matched, "Invalid feature string: $feature");
			$this->assertTrue(strlen($feature) < 250, "Feature string must be under 250 chars");
			$this->assertTrue(version_compare(elgg_get_version(), $m[1], '<'), "Feature should have been removed: $feature");
		}
	}

	public function testUnknownFeaturesEnabled() {
		$this->assertTrue(_elgg_feature_is_enabled('3.0:unknown', []));
	}
	
	public function testKnownFeatureChecksConfig() {
		$features = ['0.0:a', '0.0:b', '0.0:c'];

		set_config("feat:{$features[0]}", true);
		set_config("feat:{$features[1]}", false);

		$this->assertTrue(_elgg_feature_is_enabled($features[0], $features));
		$this->assertFalse(_elgg_feature_is_enabled($features[1], $features));
		$this->assertFalse(_elgg_feature_is_enabled($features[2], $features));

		unset_config("feat:{$features[0]}");
		unset_config("feat:{$features[1]}");
	}
}
