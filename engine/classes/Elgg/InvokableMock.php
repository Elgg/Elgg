<?php
namespace Elgg;

/**
 * Mock object usable as an invokable class name handler
 */
class InvokableMock {

	/**
	 * Recorded array of invocations
	 *
	 * Each element has keys "this", "args", and "return"
	 *
	 * @var array[]
	 */
	public static $invocations = [];

	/**
	 * If callable, this will handle the __invoke call
	 *
	 * @var callable
	 */
	public static $invoke_handler;

	/**
	 * Invoke the function
	 *
	 * @return mixed
	 */
	public function __invoke() {
		$invocation = [
			'this' => $this,
			'args' => func_get_args(),
		];

		if (is_callable(self::$invoke_handler)) {
			$invocation['return'] = call_user_func_array(self::$invoke_handler, $invocation['args']);
		} else {
			$invocation['return'] = null;
		}

		self::$invocations[] = $invocation;
		return $invocation['return'];
	}

	/**
	 * Reset the static state
	 *
	 * @return void
	 */
	public static function reset() {
		self::$invocations = [];
		self::$invoke_handler = null;
	}
}
