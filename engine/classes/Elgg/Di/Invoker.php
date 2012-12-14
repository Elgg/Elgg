<?php

/**
 * Object that invokes a callable to resolve a value
 *
 * <code>
 * $di->dough = new Elgg_Di_Invoker('Dough::factory');
 *
 * $di->cheese = new Elgg_Di_Invoker('get_cheese');
 *
 * $di->pizza = new Elgg_Di_Invoker(
 *     function ($c) {
 *         return new Pizza($c->dough, $c->cheese);
 *     },
 *     array($di));
 * </code>
 *
 * @access private
 */
class Elgg_Di_Invoker implements Elgg_Di_ResolvableInterface {

	protected $callable;
	protected $arguments;

	/**
	 * @param callable $callable
	 * @param array $arguments
	 * @throws InvalidArgumentException
	 */
	public function __construct($callable, array $arguments = array()) {
		if (!is_callable($callable, true)) {
			throw new InvalidArgumentException('Factory must be callable');
		}
		$this->arguments = array_values($arguments);
		$this->callable = $callable;
	}

	/**
	 * @param Elgg_Di_Container $container
	 * @return mixed
	 * @throws ErrorException
	 */
	public function resolveValue(Elgg_Di_Container $container) {
		if (!is_callable($this->callable)) {
			throw new ErrorException('Factory is not callable');
		}
		if ($this->arguments) {
			return call_user_func_array($this->callable, $this->arguments);
		}
		return call_user_func($this->callable);
	}
}
