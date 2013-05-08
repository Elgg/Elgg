<?php

class Elgg_PluginHooksServiceTest extends PHPUnit_Framework_TestCase {
	
	public function testTriggerCallsRegisteredHandlers() {
		$hooks = new Elgg_PluginHooksService();
		
		$this->setExpectedException('InvalidArgumentException');
		
		$hooks->registerHandler('foo', 'bar', array('Elgg_PluginHooksServiceTest', 'throwInvalidArg'));

		$hooks->trigger('foo', 'bar');
	}
	
	public function testCanPassParamsAndChangeReturnValue() {
		$hooks = new Elgg_PluginHooksService();
		$hooks->registerHandler('foo', 'bar', array('Elgg_PluginHooksServiceTest', 'changeReturn'));
		
		$returnval = $hooks->trigger('foo', 'bar', array(
			'testCase' => $this,
		), 1);
		
		$this->assertEquals(2, $returnval);
	}
	
	public static function returnTwo() {
		return 2;
	}

	public static function changeReturn($foo, $bar, $returnval, $params) {
		$testCase = $params['testCase'];

		$testCase->assertEquals(1, $returnval);

		return 2;
	}

	public static function throwInvalidArg() {
		throw new InvalidArgumentException();
	}
}
