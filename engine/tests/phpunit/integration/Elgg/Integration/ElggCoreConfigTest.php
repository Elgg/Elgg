<?php

namespace Elgg\Integration;

use Elgg\IntegrationTestCase;

/**
 * Test configuration for site
 *
 * @group IntegrationTests
 */
class ElggCoreConfigTest extends IntegrationTestCase {

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
		$this->assertEquals($value, elgg_get_config($name));
		$this->assertTrue(elgg_remove_config($name));
	}

	public function testSetConfigWithUsedName() {
		$name = 'foo' . rand(0, 1000);
		$value = 'test';
		$this->assertTrue(elgg_save_config($name, 'not test'));
		$this->assertTrue(elgg_save_config($name, $value));
		$this->assertEquals($value, elgg_get_config($name));
		$this->assertTrue(elgg_remove_config($name));
	}

	public function testSetConfigWithObject() {
		$name = 'foo' . rand(0, 1000);
		$value = new \stdClass();
		$value->test = true;
		$this->assertTrue(elgg_save_config($name, $value));
		$this->assertEquals($value, elgg_get_config($name));
		$this->assertTrue(elgg_remove_config($name));
	}

	public function testSetConfigWithNonexistentName() {
		$name = 'foo' . rand(0, 1000);
		$this->assertNull(elgg_get_config($name));
	}

	public function testSetConfigWithCurrentSite() {
		$CONFIG = _elgg_services()->config;
		$name = 'foo' . rand(0, 1000);
		$value = 99;
		$this->assertTrue(elgg_save_config($name, $value));
		$this->assertEquals($value, $CONFIG->$name);
		$this->assertEquals($value, elgg_get_config($name));
		$this->assertTrue(elgg_remove_config($name));
	}

	public function testGetConfigAlreadyLoadedForCurrentSite() {
		$CONFIG = _elgg_services()->config;
		$CONFIG->foo_unit_test = 35;
		$this->assertEquals(35, _elgg_services()->config->foo_unit_test);
		unset($CONFIG->foo_unit_test);
	}

	public function testUnsetConfigWithNonexistentName() {
		$this->assertTrue(elgg_remove_config('does_not_exist'));
	}

	public function testUnsetConfigClearsGlobalForCurrentSite() {
		$CONFIG = _elgg_services()->config;
		$CONFIG->foo_unit_test = 35;
		$this->assertTrue(elgg_remove_config('foo_unit_test'));
		$this->assertNull($CONFIG->foo_unit_test);
	}

	public function testElggSaveConfigForCurrentSiteConfig() {
		$CONFIG = _elgg_services()->config;
		$name = 'foo' . rand(0, 1000);
		$value = 'test';
		$this->assertTrue(elgg_save_config($name, $value));
		$this->assertEquals($value, elgg_get_config($name));
		$this->assertEquals($value, $CONFIG->$name);
		$this->assertTrue(elgg_remove_config($name));
	}
}
