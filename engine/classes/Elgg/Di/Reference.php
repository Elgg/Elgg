<?php

/**
 * Object that resolves a value by fetching it from the container
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
