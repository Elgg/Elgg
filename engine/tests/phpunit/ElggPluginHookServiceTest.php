<?php

class ElggPluginHookServiceTest extends PHPUnit_Framework_TestCase {
	
	public function testTriggerCallsRegisteredHandlers() {
		$hooks = new ElggPluginHookService();
		
		$this->setExpectedException('InvalidArgumentException');
		
		$hooks->registerHandler('foo', 'bar', function() {
			throw new InvalidArgumentException();
		});

		$hooks->trigger('foo', 'bar');
	}
	
	public function testCanPassParamsAndChangeReturnValue() {
		$hooks = new ElggPluginHookService();
		$hooks->registerHandler('foo', 'bar', function($foo, $bar, $returnval, $params) {
			$testCase = $params['testCase'];
			
			$testCase->assertEquals(1, $returnval);
			
			return 2;
		});
		
		$returnval = $hooks->trigger('foo', 'bar', array(
			'testCase' => $this,
		), 1);
		
		$this->assertEquals(2, $returnval);
	}
	
	public function testCanUnregisterHandlers() {
		$hooks = new ElggPluginHookService();
		
		$hooks->registerHandler('foo', 'bar', array('ElggPluginHookServiceTest', 'returnTwo'));
		
		$hooks->unregisterHandler('foo', 'bar', array('ElggPluginHookServiceTest', 'returnTwo'));
		
		$returnval = $hooks->trigger('foo', 'bar', array(), 1);
		
		$this->assertEquals(1, $returnval);
	}
	
	public static function returnTwo() {
		return 2;
	}
   
}
