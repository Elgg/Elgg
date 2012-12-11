<?php

/**
 * Object that resolves a value by fetching it from the container at read-time
 *
 * <code>
 * // When this value is read, "foo" will be read from the container and used as the
 * // new shared value.
 * $container->set('sharedFoo', new Elgg_Di_Reference('foo'), true);
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
	 * @param string $name
	 */
	public function __construct($name) {
		$this->name = $name;
	}

	/**
	 * @param Elgg_Di_Container $container
	 * @return mixed
	 */
	public function resolveValue(Elgg_Di_Container $container) {
		return $container->{$this->name};
	}
}
