<?php
namespace Elgg;


class PluginHooksServiceTest extends \PHPUnit_Framework_TestCase {
	
	public function testTriggerCallsRegisteredHandlers() {
		$hooks = new \Elgg\PluginHooksService();
		
		$this->setExpectedException('InvalidArgumentException');
		
		$hooks->registerHandler('foo', 'bar', array('\Elgg\PluginHooksServiceTest', 'throwInvalidArg'));

		$hooks->trigger('foo', 'bar');
	}
	
	public function testCanPassParamsAndChangeReturnValue() {
		$hooks = new \Elgg\PluginHooksService();
		$hooks->registerHandler('foo', 'bar', array('\Elgg\PluginHooksServiceTest', 'changeReturn'));
		
		$returnval = $hooks->trigger('foo', 'bar', array(
			'testCase' => $this,
		), 1);
		
		$this->assertEquals(2, $returnval);
	}

	public function testNullReturnDoesntChangeValue() {
		$hooks = new \Elgg\PluginHooksService();
		$hooks->registerHandler('foo', 'bar', 'Elgg\Values::getNull');

		$returnval = $hooks->trigger('foo', 'bar', array(), 1);

		$this->assertEquals(1, $returnval);
	}

	public function testUncallableHandlersAreLogged() {
		$hooks = new \Elgg\PluginHooksService();

		$loggerMock = $this->getMock('\Elgg\Logger', array(), array(), '', false);
		$hooks->setLogger($loggerMock);
		$hooks->registerHandler('foo', 'bar', array(new \stdClass(), 'uncallableMethod'));

		$expectedMsg = 'handler for plugin hook [foo, bar] is not callable: (stdClass)->uncallableMethod';
		$loggerMock->expects($this->once())->method('warn')->with($expectedMsg);

		$hooks->trigger('foo', 'bar');
	}

	public static function changeReturn($foo, $bar, $returnval, $params) {
		$testCase = $params['testCase'];

		$testCase->assertEquals(1, $returnval);

		return 2;
	}

	public static function throwInvalidArg() {
		throw new \InvalidArgumentException();
	}
}

