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

		$events->registerHandler('foo', 'bar', array('\Elgg\EventsServiceTest', 'returnFalse'));
		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertFalse($events->trigger('foo', 'bar'));
		$this->assertEquals($this->counter, 0);
	}

	public function testNullDoesNotStopPropagation() {
		$events = new EventsService();

		$events->registerHandler('foo', 'bar', array('\Elgg\EventsServiceTest', 'returnNull'));
		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertTrue($events->trigger('foo', 'bar'));
		$this->assertEquals($this->counter, 1);
	}

	public function testUnstoppableEventsCantBeStoppedAndReturnTrue() {
		$events = new EventsService();

		$events->registerHandler('foo', 'bar', array('\Elgg\EventsServiceTest', 'returnFalse'));
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

		$expectedMsg = 'handler for event [foo, bar] is not callable: (stdClass)->uncallableMethod';
		$loggerMock->expects($this->once())->method('warn')->with($expectedMsg);

		$events->trigger('foo', 'bar');
	}

	public function testInvokableClassNamesGetEventObject() {
		$events = new EventsService();
		InvokableMock::reset();
		InvokableMock::$invoke_handler = function () {
			return false;
		};

		$events->registerHandler('foo', 'bar', InvokableMock::class);
		$events->registerHandler('foo', 'bar', InvokableMock::class);

		$this->assertEquals(false, $events->trigger('foo', 'bar', null));
		$this->assertCount(1, InvokableMock::$invocations);
		$this->assertCount(1, InvokableMock::$invocations[0]["args"]);
		$this->assertInstanceOf(Event::class, InvokableMock::$invocations[0]["args"][0]);
		InvokableMock::reset();
	}

	public function incrementCounter() {
		$this->counter++;
		return true;
	}

	public static function returnTrue() {
		return true;
	}

	public static function returnFalse() {
		return false;
	}

	public static function returnNull() {
		return;
	}
}

