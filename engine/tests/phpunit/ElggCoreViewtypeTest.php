<?php

class ElggCoreViewtypeTest extends \PHPUnit_Framework_TestCase {

	protected function setUp() {
		global $CURRENT_SYSTEM_VIEWTYPE, $CONFIG;
		$CURRENT_SYSTEM_VIEWTYPE = '';
		set_input('view', '');
		unset($CONFIG->view);
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
		global $CONFIG;
		$CONFIG->view = 'bar';

		$this->assertEquals('bar', elgg_get_viewtype());
	}

	public function testSettingInputDoesNotChangeViewtype() {
		$this->assertEquals('default', elgg_get_viewtype());

		set_input('view', 'foo');
		$this->assertEquals('default', elgg_get_viewtype());
	}

	public function testSettingConfigDoesNotChangeViewtype() {
		global $CONFIG;

		$this->assertEquals('default', elgg_get_viewtype());

		$CONFIG->view = 'foo';
		$this->assertEquals('default', elgg_get_viewtype());
		unset($CONFIG->view);
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
