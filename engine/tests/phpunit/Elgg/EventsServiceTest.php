<?php
namespace Elgg;


class EventsServiceTest extends \PHPUnit_Framework_TestCase {

	public $counter = 0;

	public function setUp() {
		$this->counter = 0;
	}

	public function testTriggerCallsRegisteredHandlersAndReturnsTrue() {
		$events = new \Elgg\EventsService();

		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));
		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertTrue($events->trigger('foo', 'bar'));
		$this->assertEquals($this->counter, 2);
	}

	public function testFalseStopsPropagationAndReturnsFalse() {
		$events = new \Elgg\EventsService();

		$events->registerHandler('foo', 'bar', 'Elgg\Values::getFalse');
		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertFalse($events->trigger('foo', 'bar'));
		$this->assertEquals($this->counter, 0);
	}

	public function testNullDoesNotStopPropagation() {
		$events = new \Elgg\EventsService();

		$events->registerHandler('foo', 'bar', 'Elgg\Values::getNull');
		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertTrue($events->trigger('foo', 'bar'));
		$this->assertEquals($this->counter, 1);
	}

	public function testUnstoppableEventsCantBeStoppedAndReturnTrue() {
		$events = new \Elgg\EventsService();

		$events->registerHandler('foo', 'bar', 'Elgg\Values::getFalse');
		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertTrue($events->trigger('foo', 'bar', null, array(
			\Elgg\EventsService::OPTION_STOPPABLE => false
		)));
		$this->assertEquals($this->counter, 1);
	}

	public function testUncallableHandlersAreLogged() {
		$events = new \Elgg\EventsService();

		$loggerMock = $this->getMock('\Elgg\Logger', array(), array(), '', false);
		$events->setLogger($loggerMock);
		$events->registerHandler('foo', 'bar', array(new \stdClass(), 'uncallableMethod'));

		$expectedMsg = 'handler for event [foo, bar] is not callable: (stdClass)->uncallableMethod';
		$loggerMock->expects($this->once())->method('warn')->with($expectedMsg);

		$events->trigger('foo', 'bar');
	}

	public function incrementCounter() {
		$this->counter++;
		return true;
	}
}

