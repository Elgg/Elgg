<?php
namespace Elgg;

/**
 * The result of an elgg_call() call.
 */
class CallResult {
	/**
	 * @var bool Was the method (or a substitute) called?
	 */
	public $was_called = false;

	/**
	 * @var mixed The output of the method call it was called
	 */
	public $value;
}
