<?php

class ElggEventsServiceTest extends PHPUnit_Framework_TestCase {
	
	public function testTriggerCallsRegisteredHandlers() {
		$events = new ElggEventService();

		$this->setExpectedException('InvalidArgumentException');

		$events->registerHandler('foo', 'bar', array('ElggEventsServiceTest', 'throwInvalidArg'));

		$events->trigger('foo', 'bar');
	}

	public function testBubbling() {
		$events = new ElggEventService();

		// false stops it
		$events->registerHandler('foo', 'bar', array('ElggEventsServiceTest', 'returnFalse'));
		$events->registerHandler('foo', 'bar', array('ElggEventsServiceTest', 'throwInvalidArg'));

		$events->trigger('foo', 'bar');

		// null allows it
		$events = new ElggEventService();

		// false stops it
		$events->registerHandler('foo', 'bar', array('ElggEventsServiceTest', 'returnNull'));
		$events->registerHandler('foo', 'bar', array('ElggEventsServiceTest', 'throwInvalidArg'));

		$this->setExpectedException('InvalidArgumentException');
		$events->trigger('foo', 'bar');
		
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
