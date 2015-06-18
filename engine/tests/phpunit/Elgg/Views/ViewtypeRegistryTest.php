<?php

namespace Elgg\Views;

use Elgg\Http\Input;

use PHPUnit_Framework_TestCase as TestCase;

class ViewtypeRegistryTest extends TestCase {
	public function createPlayground() {
		$config = new \stdClass();
		$input = new Input();
		$viewtypes = new ViewtypeRegistry($config, $input);
		
		_elgg_services()->setValue('input', $input);
		_elgg_services()->setValue('viewtypes', $viewtypes);
		
		return (object)[
			'viewtypes' => $viewtypes,
		];
	}
	
	public function testSetViewtypeOverridesAnyCurrentViewtypeSetting() {
		$this->createPlayground();
		
		$this->assertTrue(elgg_set_viewtype('test'));
		$this->assertEquals('test', elgg_get_viewtype());
	}

	public function testCurrentViewtypeCanBeDeterminedByARequestVariable() {
		$this->createPlayground();
		set_input('view', 'foo');
		$this->assertEquals('foo', elgg_get_viewtype());
	}

	public function testViewtypeMustBeAlphaNumeric() {
		$this->createPlayground();
		set_input('view', 'a;b');
		$this->assertEquals('default', elgg_get_viewtype());
	}
	
	public function testGetOrCreateViewtypeAlwaysReturnsTheSameInstanceForTheSameString() {
		$app = $this->createPlayground();
		
		$default1 = $app->viewtypes->get('default');
		$default2 = $app->viewtypes->get('default');
		
		$this->assertEquals($default1, $default2);
	}
}