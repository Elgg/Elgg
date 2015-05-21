<?php

namespace Elgg;

use Elgg\HooksRegistrationService\Event;

class EventsServiceTest extends \Elgg\TestCase {

	public $counter = 0;

	public function setUp() {
		$this->counter = 0;
	}

	public function testTriggerCallsRegisteredHandlersAndReturnsTrue() {
		$events = new EventsService();

		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));
		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertTrue($events->trigger('foo', 'bar'));
		$this->assertEquals($this->counter, 2);
	}

	public function testFalseStopsPropagationAndReturnsFalse() {
		$events = new EventsService();

		$events->registerHandler('foo', 'bar', 'Elgg\Values::getFalse');
		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertFalse($events->trigger('foo', 'bar'));
		$this->assertEquals($this->counter, 0);
	}

	public function testNullDoesNotStopPropagation() {
		$events = new EventsService();

		$events->registerHandler('foo', 'bar', 'Elgg\Values::getNull');
		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertTrue($events->trigger('foo', 'bar'));
		$this->assertEquals($this->counter, 1);
	}

	public function testUnstoppableEventsCantBeStoppedAndReturnTrue() {
		$events = new EventsService();

		$events->registerHandler('foo', 'bar', 'Elgg\Values::getFalse');
		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertTrue($events->trigger('foo', 'bar', null, array(
			EventsService::OPTION_STOPPABLE => false
		)));
		$this->assertEquals($this->counter, 1);
	}

	public function testUncallableHandlersAreLogged() {
		$events = new EventsService();

		_elgg_services()->logger->disable();
		$events->registerHandler('foo', 'bar', array(new \stdClass(), 'uncallableMethod'));
		$events->trigger('foo', 'bar');

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
		$events = new EventsService();
		$handler = new TestEventHandler();

		$events->registerHandler('foo', 'bar', $handler);

		$this->assertFalse($events->trigger('foo', 'bar', null));
		$this->assertCount(1, TestEventHandler::$invocations);
		$this->assertCount(1, TestEventHandler::$invocations[0]["args"]);
		$this->assertInstanceOf(Event::class, TestEventHandler::$invocations[0]["args"][0]);

		TestEventHandler::$invocations = [];
	}

	public function testInvokableClassNamesGetEventObject() {
		$events = new EventsService();

		$events->registerHandler('foo', 'bar', TestEventHandler::class);
		$events->registerHandler('foo', 'bar', TestEventHandler::class);

		$this->assertEquals(false, $events->trigger('foo', 'bar', null));
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
