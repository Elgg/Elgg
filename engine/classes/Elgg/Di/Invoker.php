<?php

/**
 * Object that invokes a callable to resolve a value
 *
 * <code>
 * $container->set('dough', new Elgg_Di_Invoker('Dough::factory'));
 *
 * $container->set('cheese', new Elgg_Di_Invoker('get_cheese'));
 *
 * $container->set('pizza', new Elgg_Di_Invoker(function (Elgg_Di_Container $c) {
 *     return new Pizza($c->dough, $c->cheese);
 * }));
 * </code>
 *
 * @access private
 */
class Elgg_Di_Invoker implements Elgg_Di_ResolvableInterface {

	protected $callable;

	/**
	 * @param callable $factory
	 * @throws InvalidArgumentException
	 */
	public function __construct($factory) {
		if (!is_callable($factory, true)) {
			throw new InvalidArgumentException('Factory must be callable');
		}
		$this->callable = $factory;
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
		return call_user_func($this->callable, $container);
	}
}
