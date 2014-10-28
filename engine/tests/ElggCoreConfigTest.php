<?php

/**
 * Test configuration for site and application (datalist)
 */
class ElggCoreConfigTest extends ElggCoreUnitTest {

	public function testSetConfigWithTooLongName() {
		// prevent the error message from being logged
		$old_log_level = _elgg_services()->logger->getLevel();
		_elgg_services()->logger->setLevel(Elgg_Logger::OFF);

		$name = '';
		for ($i = 1; $i <= 256; $i++) {
			$name .= 'a';
		}
		$this->assertFalse(set_config($name, 'foo'));

		_elgg_services()->logger->setLevel($old_log_level);
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
		$value = new stdClass();
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
		// prevent the error message from being logged
		$old_log_level = _elgg_services()->logger->getLevel();
		_elgg_services()->logger->setLevel(Elgg_Logger::OFF);

		$name = '';
		for ($i = 1; $i <= 256; $i++) {
			$name .= 'a';
		}
		$this->assertFalse(datalist_set($name, 'foo'));

		_elgg_services()->logger->setLevel($old_log_level);
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
}
