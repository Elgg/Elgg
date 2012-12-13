<?php

/**
 * Object that resolves a value by fetching it from a container at read-time.
 *
 * <code>
 * // When this value is read, "foo" will be read from the container.
 * $container->set('aliasOfFoo', new Elgg_Di_Reference('foo'));
 *
 * // When this value is read, "foo" will be read from $cont2
 * $cont2->set('fooFromContainer1', new Elgg_Di_Reference('foo', $cont1));
 *
 * // When this value is read, a freshly-resolved "foo" will be returned from the container.
 * $container->set('aliasOfFoo', new Elgg_Di_Reference('new_foo()'));
 * </code>
 *
 * @access private
 */
class Elgg_Di_Reference implements Elgg_Di_ResolvableInterface {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var Elgg_Di_Container
	 */
	protected $container;

	/**
	 * @param string $name Either the name of a key in the container, or new_$name() where $name is the key to a
	 *                     resolvable value.
	 * @param Elgg_Di_Container $container By default, the value will be fetched from the container passed to
	 *                                     resolveValue(). If $container is given, the value will always fetch from it.
	 */
	public function __construct($name, Elgg_Di_Container $container = null) {
		$this->name = $name;
		$this->container = $container;
	}

	/**
	 * @param Elgg_Di_Container $container
	 * @return mixed
	 */
	public function resolveValue(Elgg_Di_Container $container) {
		if ($this->container) {
			$container = $this->container;
		}
		if (0 === strpos($this->name, 'new_') && substr($this->name, -2) === '()') {
			return $container->{substr($this->name, 0, -2)}();
		}
		return $container->{$this->name};
	}
}
