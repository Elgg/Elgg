<?php
namespace Elgg;

use Elgg\HooksRegistrationService\Hook;

class PluginHooksServiceTest extends \PHPUnit_Framework_TestCase {

	public function testTriggerCallsRegisteredHandlers() {
		$hooks = new PluginHooksService();
		
		$this->setExpectedException('InvalidArgumentException');
		
		$hooks->registerHandler('foo', 'bar', array('\Elgg\PluginHooksServiceTest', 'throwInvalidArg'));

		$hooks->trigger('foo', 'bar');
	}
	
	public function testCanPassParamsAndChangeReturnValue() {
		$hooks = new PluginHooksService();
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
		$hooks = new PluginHooksService();

		$loggerMock = $this->getMock('\Elgg\Logger', array(), array(), '', false);
		$hooks->setLogger($loggerMock);
		$hooks->registerHandler('foo', 'bar', array(new \stdClass(), 'uncallableMethod'));

		$expectedMsg = 'Handler for plugin hook [foo, bar] is not callable nor the name of a class that implements '
			. HookHandler::class . ': (stdClass)->uncallableMethod';
		$loggerMock->expects($this->once())->method('warn')->with($expectedMsg);

		$hooks->trigger('foo', 'bar');
	}

	public function testInvokableClassNamesGetHookObject() {
		$hooks = new PluginHooksService();

		// assume separate instances will be created
		// @todo should we keep instances around?
		$hooks->registerHandler('foo', 'bar', TestHookHandler::class);
		$hooks->registerHandler('foo', 'bar', TestHookHandler::class);

		$this->assertEquals(2, $hooks->trigger('foo', 'bar', null, 0));
		$this->assertCount(2, TestHookHandler::$invocations);
		$this->assertCount(1, TestHookHandler::$invocations[0]["args"]);
		$this->assertInstanceOf(Hook::class, TestHookHandler::$invocations[0]["args"][0]);
		$this->assertNotSame(
			TestHookHandler::$invocations[0]["this"],
			TestHookHandler::$invocations[1]["this"]
		);

		TestHookHandler::$invocations = [];
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
		throw new \InvalidArgumentException();
	}
}

class TestHookHandler implements HookHandler {

	public static $invocations = [];

	function __invoke(\Elgg\Hook $hook) {
		self::$invocations[] = [
			'this' => $this,
			'args' => func_get_args(),
		];
		return $hook->getValue() + 1;
	}
}
