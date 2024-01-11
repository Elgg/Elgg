<?php

namespace Elgg;

class TestableEvent {

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $type;

	/**
	 * @var callable
	 */
	public $handler;

	/**
	 * @var mixed
	 */
	public $object;

	/**
	 * @var int
	 */
	public $priority = 500;

	/**
	 * @var BaseTestCase
	 */
	protected $test_case;

	/**
	 * @var int
	 */
	protected $calls = 0;
	
	/**
	 * @var Event
	 */
	protected $before_state;
	
	/**
	 * @var mixed
	 */
	protected $result;
	
	/**
	 * @var Event
	 */
	protected $after_state;

	/**
	 * Register a new testing event handler
	 *
	 * @param BaseTestCase $test_case Test case
	 *
	 * @return TestableEvent
	 */
	public function register(BaseTestCase $test_case) {
		$this->test_case = $test_case;

		elgg_register_event_handler($this->name, $this->type, [$this, 'handler'], $this->priority);

		return $this;
	}

	/**
	 * Unregister handler
	 * @return void
	 */
	public function unregister() {
		elgg_unregister_event_handler($this->name, $this->type, [$this, 'handler']);
	}

	/**
	 * Handler event call
	 *
	 * @param Event $event Event
	 *
	 * @return mixed
	 */
	public function handler(\Elgg\Event $event) {
		$this->calls++;
		$this->before_state = clone $event; // using clone so the event isn't used by reference
		
		$this->object = $event->getObject();

		list(, $return, $event) = _elgg_services()->handlers->call($this->handler, $event, [
			$this->name,
			$this->type,
			$event->getValue(),
			$event->getParams(),
		]);

		$this->after_state = $event;
		
		if ($return !== null) {
			$this->result = $return;
		} else {
			$this->result = $event->getValue();
		}
		
		return $return;
	}
	
	/**
	 * @return mixed
	 */
	public function getResult() {
		return $this->result;
	}

	/**
	 * Assert that event was called expected number of times
	 *
	 * @param int $expected Expectation
	 */
	public function assertNumberOfCalls($expected) {
		$this->test_case->assertEquals($expected, $this->calls);
	}

	/**
	 * Assert that object passed to event has a specific value
	 *
	 * @param mixed $object Object
	 */
	public function assertObject($object) {
		$this->test_case->assertEquals($this->object, $object);
	}
	
	/**
	 * Assert that before event handler is called the named parameter had a specific value
	 *
	 * @param string $name  Name
	 * @param mixed  $value Value
	 */
	public function assertParamBefore($name, $value) {
		$this->test_case->assertElggDataEquals($this->before_state->getParam($name), $value);
	}
	
	/**
	 * Assert that before event handler is called the named parameter has a specific
	 *
	 * @param string $name  Name
	 * @param mixed  $value Value
	 */
	public function assertParamAfter($name, $value) {
		$this->test_case->assertElggDataEquals($this->after_state->getParam($name), $value);
	}
	
	/**
	 * Assert event value before the handler was called
	 *
	 * @param mixed $value Value
	 */
	public function assertValueBefore($value) {
		$this->test_case->assertElggDataEquals($this->before_state->getValue(), $value);
	}
	
	/**
	 * Assert event value after the handler was called
	 *
	 * @param mixed $value Value
	 */
	public function assertValueAfter($value) {
		$this->test_case->assertElggDataEquals($this->after_state->getValue(), $value);
	}
}
