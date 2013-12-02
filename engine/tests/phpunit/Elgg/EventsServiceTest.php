<?php

class Elgg_EventsServiceTest extends PHPUnit_Framework_TestCase {

	public $capturedArguments;

	public function setUp() {
		$this->capturedArguments = null;
	}

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

	public function testHandlersReceiveCorrectArguments() {
		$events = new Elgg_EventsService();

		$events->registerHandler('foo', 'bar', array($this, 'captureArguments'));

		$object = (object) array();
		$params = (object) array();

		$events->trigger('foo', 'bar', $object);

		$expected = array('foo', 'bar', $object, null);
		$this->assertEquals($expected, $this->capturedArguments);

		$events->trigger('foo', 'bar', $object, $params);

		$expected = array('foo', 'bar', $object, $params);
		$this->assertEquals($expected, $this->capturedArguments);
	}

	public function captureArguments() {
		$this->capturedArguments = func_get_args();
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
