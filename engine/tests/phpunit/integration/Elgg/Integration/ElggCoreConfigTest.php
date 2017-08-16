<?php

namespace Elgg\Integration;

use Elgg\LegacyIntegrationTestCase;

/**
 * Test configuration for site
 *
 * @group IntegrationTests
 */
class ElggCoreConfigTest extends LegacyIntegrationTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testSetConfigWithTooLongName() {
		_elgg_services()->logger->disable();

		$name = '';
		for ($i = 1; $i <= 256; $i++) {
			$name .= 'a';
		}
		$this->assertFalse(elgg_save_config($name, 'foo'));

		_elgg_services()->logger->enable();
	}

	public function testSetConfigWithNewName() {
		$name = 'foo' . rand(0, 1000);
		$value = 'test';
		$this->assertTrue(elgg_save_config($name, $value));
		$this->assertEqual($value, elgg_get_config($name));
		$this->assertTrue(elgg_remove_config($name));
	}

	public function testSetConfigWithUsedName() {
		$name = 'foo' . rand(0, 1000);
		$value = 'test';
		$this->assertTrue(elgg_save_config($name, 'not test'));
		$this->assertTrue(elgg_save_config($name, $value));
		$this->assertEqual($value, elgg_get_config($name));
		$this->assertTrue(elgg_remove_config($name));
	}

	public function testSetConfigWithObject() {
		$name = 'foo' . rand(0, 1000);
		$value = new \stdClass();
		$value->test = true;
		$this->assertTrue(elgg_save_config($name, $value));
		$this->assertIdentical($value, elgg_get_config($name));
		$this->assertTrue(elgg_remove_config($name));
	}

	public function testSetConfigWithNonexistentName() {
		$name = 'foo' . rand(0, 1000);
		$this->assertIdentical(null, elgg_get_config($name));
	}

	public function testSetConfigWithCurrentSite() {
		$CONFIG = _elgg_config();
		$name = 'foo' . rand(0, 1000);
		$value = 99;
		$this->assertTrue(elgg_save_config($name, $value));
		$this->assertIdentical($value, $CONFIG->$name);
		$this->assertIdentical($value, elgg_get_config($name));
		$this->assertTrue(elgg_remove_config($name));
	}

	public function testGetConfigAlreadyLoadedForCurrentSite() {
		$CONFIG = _elgg_config();
		$CONFIG->foo_unit_test = 35;
		$this->assertIdentical(35, _elgg_config()->foo_unit_test);
		unset($CONFIG->foo_unit_test);
	}

	public function testUnsetConfigWithNonexistentName() {
		$this->assertTrue(elgg_remove_config('does_not_exist'));
	}

	public function testUnsetConfigClearsGlobalForCurrentSite() {
		$CONFIG = _elgg_config();
		$CONFIG->foo_unit_test = 35;
		$this->assertIdentical(true, elgg_remove_config('foo_unit_test'));
		$this->assertTrue(!isset($CONFIG->foo_unit_test));
	}

	public function testElggSaveConfigForCurrentSiteConfig() {
		$CONFIG = _elgg_config();
		$name = 'foo' . rand(0, 1000);
		$value = 'test';
		$this->assertTrue(elgg_save_config($name, $value));
		$this->assertIdentical($value, elgg_get_config($name));
		$this->assertIdentical($value, $CONFIG->$name);
		$this->assertTrue(elgg_remove_config($name));
	}
}
