<?php

namespace Elgg;

/**
 * @group UnitTests
 */
class PluginHooksServiceUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;
	
	public function up() {
		$this->hooks = new PluginHooksService(_elgg_services()->events);
	}

	public function down() {

	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testTriggerCallsRegisteredHandlers() {
		$this->hooks->registerHandler('foo', 'bar', [
			PluginHooksServiceUnitTest::class,
			'throwInvalidArg'
		]);

		$this->hooks->trigger('foo', 'bar');
	}

	public function testCanPassParamsAndChangeReturnValue() {
		$this->hooks->registerHandler('foo', 'bar', [
			PluginHooksServiceUnitTest::class,
			'changeReturn'
		]);

		$returnval = $this->hooks->trigger('foo', 'bar', array(
			'testCase' => $this,
		), 1);

		$this->assertEquals(2, $returnval);
	}

	public function testCanPassHookObjectAndChangeReturnValue() {
		$this->hooks->registerHandler('foo', 'bar', [
			PluginHooksServiceUnitTest::class,
			'changeReturn2'
		]);

		$returnval = $this->hooks->trigger('foo', 'bar', array(
			'testCase' => $this,
		), 1);

		$this->assertEquals(2, $returnval);
	}

	public function testNullReturnDoesntChangeValue() {
		$this->hooks->registerHandler('foo', 'bar', [Values::class, 'getNull']);

		$returnval = $this->hooks->trigger('foo', 'bar', array(), 1);

		$this->assertEquals(1, $returnval);
	}

	public function testUncallableHandlersAreLogged() {
		_elgg_services()->logger->disable();

		$this->hooks->registerHandler('foo', 'bar', array(
			new \stdClass(),
			'uncallableMethod'
		));
		$this->hooks->trigger('foo', 'bar');

		$logged = _elgg_services()->logger->enable();

		$this->assertSame([
			[
				'message' => 'Handler for hook [foo, bar] is not callable: (stdClass)->uncallableMethod',
				'level' => 300,
			],
		], $logged);
	}

	public function testHookTypeHintReceivesObject() {
		$handler = new TestHookHandler();

		$this->hooks->registerHandler('foo', 'bar', $handler);

		$this->assertEquals(3, $this->hooks->trigger('foo', 'bar', array('foo' => 1), 2));
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
		/* @var PluginHooksServiceUnitTest $testCase */

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
