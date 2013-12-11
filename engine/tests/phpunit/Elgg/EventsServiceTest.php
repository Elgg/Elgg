<?php

class Elgg_EventsServiceTest extends PHPUnit_Framework_TestCase {

	public $counter = 0;

	public function setUp() {
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

		$events->registerHandler('foo', 'bar', array('Elgg_EventsServiceTest', 'returnFalse'));
		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertFalse($events->trigger('foo', 'bar'));
		$this->assertEquals($this->counter, 0);
	}

	public function testNullDoesNotStopPropagation() {
		$events = new Elgg_EventsService();

		$events->registerHandler('foo', 'bar', array('Elgg_EventsServiceTest', 'returnNull'));
		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertTrue($events->trigger('foo', 'bar'));
		$this->assertEquals($this->counter, 1);
	}

	public function testUnstoppableEventsCantBeStoppedAndReturnTrue() {
		$events = new Elgg_EventsService();

		$events->registerHandler('foo', 'bar', array('Elgg_EventsServiceTest', 'returnFalse'));
		$events->registerHandler('foo', 'bar', array($this, 'incrementCounter'));

		$this->assertTrue($events->trigger('foo', 'bar', null, array(
			Elgg_EventsService::OPTION_STOPPABLE => false
		)));
		$this->assertEquals($this->counter, 1);
	}

	public function incrementCounter() {
		$this->counter++;
		return true;
	}

	public static function throwInvalidArg() {
		throw new InvalidArgumentException();
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
