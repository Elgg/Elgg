<?php

namespace Elgg;

use Elgg\HooksRegistrationService\Event;
use Psr\Log\LogLevel;

/**
 * @group UnitTests
 */
class EventsServiceUnitTest extends \Elgg\UnitTestCase {

	public $counter = 0;

	/**
	 * @var EventsService
	 */
	public $events;

	public function up() {
		$this->counter = 0;
		$this->events = new EventsService(new HandlersService());
	}

	public function down() {

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
				'level' => 'warning',
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
	
	public function testDeprecatedWithoutRegisteredHandlers() {
		
		_elgg_services()->logger->disable();
		
		$this->assertTrue($this->events->triggerDeprecated('foo', 'bar', null, 'The event "foo":"bar" has been deprecated', '1.0'));
		
		$logged = _elgg_services()->logger->enable();
		
		$this->assertEquals([], $logged);
	}
	
	public function testDeprecatedWithRegisteredHandlers() {
		
		$this->events->registerHandler('foo', 'bar', [$this, 'incrementCounter']);
		
		_elgg_services()->logger->disable();
		
		$this->assertTrue($this->events->triggerDeprecated('foo', 'bar', null, 'The event "foo":"bar" has been deprecated', '1.0'));
		$this->assertEquals(1, $this->counter);
		
		$logged = _elgg_services()->logger->enable();
		$this->assertCount(1, $logged);
		
		$message_details = $logged[0];
		
		$this->assertArrayHasKey('message', $message_details);
		$this->assertArrayHasKey('level', $message_details);
		$this->assertStringStartsWith('Deprecated in 1.0: The event "foo":"bar" has been deprecated Called from', $message_details['message']);
		$this->assertEquals(LogLevel::WARNING, $message_details['level']);
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
