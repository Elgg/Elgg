<?php

class Elgg_EventsServiceTest extends PHPUnit_Framework_TestCase {

	public $capturedArguments;
	public $counter = 0;

	public function setUp() {
		$this->capturedArguments = null;
		$this->counter = 0;
	}

	public function testTriggerCallsRegisteredHandlersAndReturnsTrue() {
		$events = new Elgg_EventsService();

		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));
		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertTrue($events->trigger('foo', 'bar'));
		$this->assertEquals($this->counter, 2);
	}

	public function testFalseStopsPropagationAndReturnsFalse() {
		$events = new Elgg_EventsService();

		$events->registerHandler('foo', 'bar', __CLASS__ . '::returnFalse');
		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertFalse($events->trigger('foo', 'bar'));
		$this->assertEquals($this->counter, 0);
	}

	public function testNullDoesNotStopPropagation() {
		$events = new Elgg_EventsService();

		$events->registerHandler('foo', 'bar', __CLASS__ . '::returnNull');
		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertTrue($events->trigger('foo', 'bar'));
		$this->assertEquals($this->counter, 1);
	}

	public function testUnstoppableEventsCantBeStoppedAndReturnTrue() {
		$events = new Elgg_EventsService();

		$events->registerHandler('foo', 'bar', __CLASS__ . '::returnFalse');
		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertTrue($events->trigger('foo', 'bar', null, null, array(
			Elgg_EventsService::OPTION_STOPPABLE => false
		)));
		$this->assertEquals($this->counter, 1);
	}

	public function testUncallableHandlersAreLogged() {
		$events = new Elgg_EventsService();

		$loggerMock = $this->getMock('Elgg_Logger', array(), array(), '', false);
		$events->setLogger($loggerMock);
		$events->registerHandler('foo', 'bar', array(new stdClass(), 'uncallableMethod'));

		$expectedMsg = 'handler for event [foo, bar] is not callable: (stdClass)->uncallableMethod';
		$loggerMock->expects($this->once())->method('warn')->with($expectedMsg);

		$events->trigger('foo', 'bar');
	}

	public function incrementCounter() {
		$this->counter++;
		return true;
	}

	public function testHandlersReceiveCorrectArguments() {
		$events = new Elgg_EventsService();

		$events->registerHandler('foo', 'bar', array($this, 'captureArguments'));

		$object = (object) array();
		$params = (object) array('prop' => 'val');

		$events->trigger('foo', 'bar', $object, $params);

		$expected = array('foo', 'bar', $object, $params);
		$this->assertEquals($expected, $this->capturedArguments);
	}

	public function captureArguments() {
		$this->capturedArguments = func_get_args();
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
