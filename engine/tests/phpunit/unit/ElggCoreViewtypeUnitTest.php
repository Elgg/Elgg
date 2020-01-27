<?php

/**
 * @group UnitTests
 */
class ElggCoreViewtypeUnitTest extends \Elgg\UnitTestCase {

	public function up() {
		set_input('view', '');
		elgg_set_config('view', null);
		elgg_set_viewtype('');
	}

	public function down() {
		$this->setUp();
	}

	public function testElggSetViewtype() {
		$this->assertTrue(elgg_set_viewtype('test'));
		$this->assertEquals('test', elgg_get_viewtype());
	}

	public function testDefaultViewtype() {
		$this->assertEquals('default', elgg_get_viewtype());
	}

	public function testInputSetsInitialViewtype() {
		set_input('view', 'foo');
		$this->assertEquals('foo', elgg_get_viewtype());
	}

	public function testConfigSetsInitialViewtype() {
		elgg_set_config('view', 'bar');

		$this->assertEquals('bar', elgg_get_viewtype());
	}

	public function testSettingInputDoesNotChangeViewtype() {
		$this->assertEquals('default', elgg_get_viewtype());

		set_input('view', 'foo');
		$this->assertEquals('default', elgg_get_viewtype());
	}

	public function testSettingConfigDoesNotChangeViewtype() {
		$this->assertEquals('default', elgg_get_viewtype());

		elgg_set_config('view', 'foo');
		$this->assertEquals('default', elgg_get_viewtype());
		elgg_set_config('view', null);
	}

	public function testElggIsValidViewtype() {
		$this->assertTrue(_elgg_services()->views->isValidViewtype('valid'));
		$this->assertTrue(_elgg_services()->views->isValidViewtype('valid_viewtype'));
		$this->assertTrue(_elgg_services()->views->isValidViewtype('0'));

		$this->assertFalse(_elgg_services()->views->isValidViewtype('a;b'));
		$this->assertFalse(_elgg_services()->views->isValidViewtype('invalid-viewtype'));
		$this->assertFalse(_elgg_services()->views->isValidViewtype(123));
		$this->assertFalse(_elgg_services()->views->isValidViewtype(''));
	}

}
