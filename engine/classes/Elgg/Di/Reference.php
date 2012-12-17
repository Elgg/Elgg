<?php

/**
 * Object that resolves a value by fetching it from a container at read-time.
 *
 * <code>
 * // When this value is read, "foo" will be read from the container.
 * $cont1->setFactory('aliasOfFoo', new Elgg_Di_Reference('foo'));
 * </code>
 *
 * @access private
 */
class Elgg_Di_Reference implements Elgg_Di_FactoryInterface {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @param string $name Either the name of a key in the container, or new_$name() where $name is the key to a
	 *                     resolvable value.
	 */
	public function __construct($name) {
		$this->name = $name;
	}

	/**
	 * @param Elgg_Di_Container $container
	 * @return mixed
	 */
	public function createValue(Elgg_Di_Container $container) {
		return $container->get($this->name);
	}
}
