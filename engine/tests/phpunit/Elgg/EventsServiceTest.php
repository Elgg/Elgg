<?php
namespace Elgg;

use Elgg\HooksRegistrationService\Event;

class EventsServiceTest extends \PHPUnit_Framework_TestCase {

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

		$loggerMock = $this->getMock('\Elgg\Logger', array(), array(), '', false);
		$events->setLogger($loggerMock);
		$events->registerHandler('foo', 'bar', array(new \stdClass(), 'uncallableMethod'));

		$expectedMsg = 'Handler for event [foo, bar] is not callable nor the name of a class that implements '
			. EventHandler::class . ': (stdClass)->uncallableMethod';
		$loggerMock->expects($this->once())->method('warn')->with($expectedMsg);

		$events->trigger('foo', 'bar');
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

class TestEventHandler implements EventHandler {

	public static $invocations = [];

	function __invoke(\Elgg\Event $event) {
		self::$invocations[] = [
			'this' => $this,
			'args' => func_get_args(),
		];
		return false;
	}
}