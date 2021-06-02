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
		
		$this->object = $event->getObject();

		list(, $return, $event) = _elgg_services()->handlers->call($this->handler, $event, [
			$this->name,
			$this->type,
			$this->object,
		]);

		return $return;
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
}
