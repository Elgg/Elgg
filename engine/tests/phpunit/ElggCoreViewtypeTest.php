<?php

$engine = dirname(dirname(dirname(__FILE__)));
require_once "$engine/lib/views.php";
require_once "$engine/lib/input.php";
require_once "$engine/lib/pageowner.php";

global $CONFIG;
$CONFIG->context = array();


class ElggCoreViewtypeTest extends PHPUnit_Framework_TestCase {

	protected function setUp() {
		global $CURRENT_SYSTEM_VIEWTYPE;
		$CURRENT_SYSTEM_VIEWTYPE = '';
	}

	public function testElggSetViewtype() {
		$this->assertTrue(elgg_set_viewtype('test'));
		$this->assertEquals('test', elgg_get_viewtype());
	}

	public function testElggGetViewtype() {
		$this->assertEquals('default', elgg_get_viewtype());

		set_input('view', 'foo');
		$this->assertEquals('foo', elgg_get_viewtype());

		set_input('view', 'a;b');
		$this->assertEquals('default', elgg_get_viewtype());
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
