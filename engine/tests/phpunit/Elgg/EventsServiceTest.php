<?php

namespace Elgg;

use Elgg\HooksRegistrationService\Event;

class EventsServiceTest extends \Elgg\TestCase {

	public $counter = 0;

	/**
	 * @var EventsService
	 */
	public $events;

	public function setUp() {
		$this->counter = 0;
		$this->events = new EventsService(new HandlersService());
	}

	public function testTriggerCallsRegisteredHandlersAndReturnsTrue() {
		$this->events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));
		$this->events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertTrue($this->events->trigger('foo', 'bar'));
		$this->assertEquals($this->counter, 2);
	}

	public function testFalseStopsPropagationAndReturnsFalse() {
		$this->events->registerHandler('foo', 'bar', 'Elgg\Values::getFalse');
		$this->events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertFalse($this->events->trigger('foo', 'bar'));
		$this->assertEquals($this->counter, 0);
	}

	public function testNullDoesNotStopPropagation() {
		$this->events->registerHandler('foo', 'bar', 'Elgg\Values::getNull');
		$this->events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertTrue($this->events->trigger('foo', 'bar'));
		$this->assertEquals($this->counter, 1);
	}

	public function testUnstoppableEventsCantBeStoppedAndReturnTrue() {
		$this->events->registerHandler('foo', 'bar', 'Elgg\Values::getFalse');
		$this->events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertTrue($this->events->trigger('foo', 'bar', null, array(
			EventsService::OPTION_STOPPABLE => false
		)));
		$this->assertEquals($this->counter, 1);
	}

	public function testUncallableHandlersAreLogged() {
		_elgg_services()->logger->disable();
		$this->events->registerHandler('foo', 'bar', array(new \stdClass(), 'uncallableMethod'));
		$this->events->trigger('foo', 'bar');

		$logged = _elgg_services()->logger->enable();

		$expected = [
			[
				'message' => 'Handler for event [foo, bar] is not callable: (stdClass)->uncallableMethod',
				'level' => 300,
			]
		];
		$this->assertSame($expected, $logged);
	}

	public function testEventTypeHintReceivesObject() {
		$handler = new TestEventHandler();

		$this->events->registerHandler('foo', 'bar', $handler);

		$this->assertFalse($this->events->trigger('foo', 'bar', null));
		$this->assertCount(1, TestEventHandler::$invocations);
		$this->assertCount(1, TestEventHandler::$invocations[0]["args"]);
		$this->assertInstanceOf(Event::class, TestEventHandler::$invocations[0]["args"][0]);

		TestEventHandler::$invocations = [];
	}

	public function testInvokableClassNamesGetEventObject() {
		$this->events->registerHandler('foo', 'bar', TestEventHandler::class);
		$this->events->registerHandler('foo', 'bar', TestEventHandler::class);

		$this->assertEquals(false, $this->events->trigger('foo', 'bar', null));
		$this->assertCount(1, TestEventHandler::$invocations);
		$this->assertCount(1, TestEventHandler::$invocations[0]["args"]);
		$this->assertInstanceOf(Event::class, TestEventHandler::$invocations[0]["args"][0]);

		TestEventHandler::$invocations = [];
	}

	public function incrementCounter() {
		$this->counter++;
		return true;
	}
}

class TestEventHandler {

	public static $invocations = [];

	function __invoke(\Elgg\Event $event) {
		self::$invocations[] = [
			'this' => $this,
			'args' => func_get_args(),
		];
		return false;
	}
}
