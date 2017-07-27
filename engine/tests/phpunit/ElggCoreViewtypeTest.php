<?php

class ElggCoreViewtypeTest extends \Elgg\TestCase {

	protected function setUp() {
		set_input('view', '');
		elgg_set_config('view', null);
		elgg_set_viewtype('');
	}

	protected function tearDown() {
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
		$this->assertTrue(_elgg_is_valid_viewtype('valid'));
		$this->assertTrue(_elgg_is_valid_viewtype('valid_viewtype'));
		$this->assertTrue(_elgg_is_valid_viewtype('0'));

		$this->assertFalse(_elgg_is_valid_viewtype('a;b'));
		$this->assertFalse(_elgg_is_valid_viewtype('invalid-viewtype'));
		$this->assertFalse(_elgg_is_valid_viewtype(123));
		$this->assertFalse(_elgg_is_valid_viewtype(''));
	}

}
