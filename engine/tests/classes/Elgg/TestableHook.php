<?php

namespace Elgg;

class TestableHook {

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
	 * @var Hook
	 */
	protected $before_state;

	/**
	 * @var mixed
	 */
	protected $result;

	/**
	 * @var Hook
	 */
	protected $after_state;

	/**
	 * Register a new testing hook handler
	 *
	 * @param BaseTestCase $test_case Test case
	 *
	 * @return TestableHook
	 */
	public function register(BaseTestCase $test_case) {
		$this->test_case = $test_case;

		elgg_register_plugin_hook_handler($this->name, $this->type, [$this, 'handler'], $this->priority);

		return $this;
	}

	/**
	 * Unregister handler
	 * @return void
	 */
	public function unregister() {
		elgg_unregister_plugin_hook_handler($this->name, $this->type, [$this, 'handler']);
	}

	/**
	 * Handler hook call
	 *
	 * @param Hook $hook Hook
	 *
	 * @return mixed
	 */
	public function handler(\Elgg\Hook $hook) {
		$this->calls++;
		$this->before_state = clone $hook; // using clone so the hook isn't used by reference

		list(, $return, $hook) = _elgg_services()->handlers->call($this->handler, $hook, [
			$this->name,
			$this->type,
			$hook->getValue(),
			$hook->getParams(),
		]);

		$this->after_state = $hook;

		if ($return !== null) {
			$this->result = $return;
		} else {
			$this->result = $hook->getValue();
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
	 * Assert that hook was called expected number of times
	 *
	 * @param int $expected Expectation
	 */
	public function assertNumberOfCalls($expected) {
		$this->test_case->assertEquals($expected, $this->calls);
	}

	/**
	 * Assert that before hook handler is called the named parameter had a specific value
	 *
	 * @param string $name  Name
	 * @param mixed  $value Value
	 */
	public function assertParamBefore($name, $value) {
		$this->test_case->assertEquals($this->before_state->getParam($name), $value);
	}

	/**
	 * Assert that before hook handler is called the named parameter has a specific
	 *
	 * @param string $name  Name
	 * @param mixed  $value Value
	 */
	public function assertParamAfter($name, $value) {
		$this->test_case->assertEquals($this->after_state->getParam($name), $value);
	}

	/**
	 * Assert hook value before the handler was called
	 *
	 * @param mixed $value Value
	 */
	public function assertValueBefore($value) {
		$this->test_case->assertEquals($this->before_state->getValue(), $value);
	}

	/**
	 * Assert hook value after the handler was called
	 *
	 * @param mixed $value Value
	 */
	public function assertValueAfter($value) {
		$this->test_case->assertEquals($this->after_state->getValue(), $value);
	}
}
