<?php

namespace Elgg;

class PluginHooksServiceTest extends \Elgg\TestCase {

	public function testTriggerCallsRegisteredHandlers() {
		$hooks = new PluginHooksService();

		$this->setExpectedException(\InvalidArgumentException::class);

		$hooks->registerHandler('foo', 'bar', [
			PluginHooksServiceTest::class,
			'throwInvalidArg'
		]);

		$hooks->trigger('foo', 'bar');
	}

	public function testCanPassParamsAndChangeReturnValue() {
		$hooks = new PluginHooksService();
		$hooks->registerHandler('foo', 'bar', [
			PluginHooksServiceTest::class,
			'changeReturn'
		]);

		$returnval = $hooks->trigger('foo', 'bar', array(
			'testCase' => $this,
		), 1);

		$this->assertEquals(2, $returnval);
	}

	public function testCanPassHookObjectAndChangeReturnValue() {
		$hooks = new PluginHooksService();
		$hooks->registerHandler('foo', 'bar', [
			PluginHooksServiceTest::class,
			'changeReturn2'
		]);

		$returnval = $hooks->trigger('foo', 'bar', array(
			'testCase' => $this,
		), 1);

		$this->assertEquals(2, $returnval);
	}

	public function testNullReturnDoesntChangeValue() {
		$hooks = new PluginHooksService();
		$hooks->registerHandler('foo', 'bar', [Values::class, 'getNull']);

		$returnval = $hooks->trigger('foo', 'bar', array(), 1);

		$this->assertEquals(1, $returnval);
	}

	public function testUncallableHandlersAreLogged() {
		$hooks = new PluginHooksService();

		_elgg_services()->logger->disable();

		$hooks->registerHandler('foo', 'bar', array(
			new \stdClass(),
			'uncallableMethod'
		));
		$hooks->trigger('foo', 'bar');

		$logged = _elgg_services()->logger->enable();

		$this->assertSame([
			[
				'message' => 'Handler for hook [foo, bar] is not callable: (stdClass)->uncallableMethod',
				'level' => 300,
			],
		], $logged);
	}

	public function testHookTypeHintReceivesObject() {
		$hooks = new PluginHooksService();
		$handler = new TestHookHandler();

		$hooks->registerHandler('foo', 'bar', $handler);

		$this->assertEquals(3, $hooks->trigger('foo', 'bar', array('foo' => 1), 2));
		$this->assertCount(1, TestHookHandler::$invocations);
		$this->assertCount(1, TestHookHandler::$invocations[0]["args"]);
		$this->assertInstanceOf(Hook::class, TestHookHandler::$invocations[0]["args"][0]);

		TestHookHandler::$invocations = [];
	}

	public static function returnTwo() {
		return 2;
	}

	public static function changeReturn($foo, $bar, $returnval, $params) {
		$testCase = $params['testCase'];
		/* @var PluginHooksServiceTest $testCase */

		$testCase->assertEquals(1, $returnval);

		return 2;
	}

	public static function changeReturn2(\Elgg\Hook $hook) {
		$testCase = $hook->getParam('testCase');

		$testCase->assertEquals(1, $hook->getValue());

		return 2;
	}

	public static function throwInvalidArg() {
		throw new \InvalidArgumentException();
	}
}

class TestHookHandler {

	public static $invocations = [];

	function __invoke(\Elgg\Hook $hook) {
		self::$invocations[] = [
			'this' => $this,
			'args' => func_get_args(),
		];
		return $hook->getValue() + 1;
	}
}
