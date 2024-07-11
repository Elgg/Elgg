<?php

namespace Elgg;

use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Helpers\TestEventResultsHandler;
use Psr\Log\LogLevel;

class EventsServiceResultsUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var EventsService
	 */
	protected $events;
	
	public function up() {
		$this->events = new EventsService(_elgg_services()->handlers);
		_elgg_services()->logger->disable();
	}
	
	public function down() {
		_elgg_services()->logger->enable();
	}

	public function testTriggerCallsRegisteredHandlers() {
		$this->events->registerHandler('foo', 'bar', [
			EventsServiceResultsUnitTest::class,
			'throwInvalidArg'
		]);

		$this->expectException(InvalidArgumentException::class);
		$this->events->triggerResults('foo', 'bar');
	}

	public function testCanPassParamsAndChangeReturnValue() {
		$this->events->registerHandler('foo', 'bar', [
			EventsServiceResultsUnitTest::class,
			'changeReturn'
		]);

		$returnval = $this->events->triggerResults('foo', 'bar', array(
			'testCase' => $this,
		), 1);

		$this->assertEquals(2, $returnval);
	}

	public function testCanPassHookObjectAndChangeReturnValue() {
		$this->events->registerHandler('foo', 'bar', [
			EventsServiceResultsUnitTest::class,
			'changeReturn2'
		]);

		$returnval = $this->events->triggerResults('foo', 'bar', array(
			'testCase' => $this,
		), 1);

		$this->assertEquals(2, $returnval);
	}

	public function testNullReturnDoesntChangeValue() {
		$this->events->registerHandler('foo', 'bar', [Values::class, 'getNull']);

		$returnval = $this->events->triggerResults('foo', 'bar', array(), 1);

		$this->assertEquals(1, $returnval);
	}

	public function testUncallableHandlersAreLogged() {
		$this->events->registerHandler('foo', 'bar', array(
			new \stdClass(),
			'uncallableMethod'
		));
		
		_elgg_services()->logger->disable();

		$this->events->triggerResults('foo', 'bar');

		$logged = _elgg_services()->logger->enable();

		$this->assertSame([
			[
				'message' => 'Handler for event [foo, bar] is not callable: (stdClass)->uncallableMethod',
				'level' => 'warning',
			],
		], $logged);
	}

	public function testHookTypeHintReceivesObject() {
		$handler = new TestEventResultsHandler();

		$this->events->registerHandler('foo', 'bar', $handler);

		$this->assertEquals(3, $this->events->triggerResults('foo', 'bar', array('foo' => 1), 2));
		$this->assertCount(1, TestEventResultsHandler::$invocations);
		$this->assertCount(1, TestEventResultsHandler::$invocations[0]["args"]);
		$this->assertInstanceOf(Event::class, TestEventResultsHandler::$invocations[0]["args"][0]);

		TestEventResultsHandler::$invocations = [];
	}

	public static function returnTwo() {
		return 2;
	}

	public static function changeReturn(\Elgg\Event $event) {
		$testCase = $event->getParam('testCase');
		/* @var EventsServiceResultsUnitTest $testCase */

		$testCase->assertEquals(1, $event->getValue());

		return 2;
	}

	public static function changeReturn2(\Elgg\Event $event) {
		$testCase = $event->getParam('testCase');

		$testCase->assertEquals(1, $event->getValue());

		return 2;
	}

	public static function throwInvalidArg() {
		throw new InvalidArgumentException();
	}
}
