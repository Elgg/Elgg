<?php

class Elgg_EventsServiceTest extends PHPUnit_Framework_TestCase {
	
	public function testTriggerCallsRegisteredHandlers() {
		$events = new Elgg_EventsService();

		$this->setExpectedException('InvalidArgumentException');

		$events->registerHandler('foo', 'bar', array('Elgg_EventsServiceTest', 'throwInvalidArg'));

		$events->trigger('foo', 'bar');
	}

	public function testBubbling() {
		$events = new Elgg_EventsService();

		// false stops it
		$events->registerHandler('foo', 'bar', array('Elgg_EventsServiceTest', 'returnFalse'));
		$events->registerHandler('foo', 'bar', array('Elgg_EventsServiceTest', 'throwInvalidArg'));

		$events->trigger('foo', 'bar');

		// null allows it
		$events = new Elgg_EventsService();

		// false stops it
		$events->registerHandler('foo', 'bar', array('Elgg_EventsServiceTest', 'returnNull'));
		$events->registerHandler('foo', 'bar', array('Elgg_EventsServiceTest', 'throwInvalidArg'));

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
