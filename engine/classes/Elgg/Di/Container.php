<?php

/**
 * Container holding values which can be resolved upon reading and optionally stored and shared
 * across reads.
 *
 * Values are read as properties, but must be set via set().
 *
 * @access private
 */
class Elgg_Di_Container {

	/**
	 * @var Elgg_Di_Core
	 */
	protected $_core;

	/**
	 * @param Elgg_Di_Core $core
	 */
	public function __construct(Elgg_Di_Core $core = null) {
		if (!$core) {
			$core = new Elgg_Di_Core();
		}
		$this->_core = $core;
	}

	/**
	 * Fetch a value that must be resolved. If the resolved value can be shared, it is placed
	 * as a dynamic property on the container.
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		$value = $this->_core->get($name, $this);
		if ($this->_core->isShared($name)) {
			// cache as dynamic property
			$this->{$name} = $value;
			// git rid of resolver object
			$this->_core->set($name, $value);
		}
		return $value;
	}

	/**
	 * Does the container have this value or know how to retrieve it?
	 *
	 * @param string $name
	 * @return bool
	 */
	public function has($name) {
		return property_exists($this, $name) || $this->_core->has($name);
	}

	/**
	 * Will reads return the same value?
	 *
	 * @param string $name
	 * @return bool
	 */
	public function isShared($name) {
		return property_exists($this, $name) || $this->_core->has($name);
	}

	/**
	 * Store a value or an object that can be resolved to a value.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param bool $share Share the value between reads? If the value does not implement Elgg_Di_ResolvableInterface, it's always shared
	 * @return Elgg_ServiceProvider
	 * @throws InvalidArgumentException
	 */
	public function set($name, $value, $share = false) {
		if ($name[0] === '_') {
			throw new InvalidArgumentException('Name cannot begin with underscore');
		}
		$this->remove($name);
		if ($value instanceof Elgg_Di_ResolvableInterface) {
			$this->_core->set($name, $value, $share);
		} else {
			$this->{$name} = $value;
		}
		return $this;
	}

	/**
	 * Remove a value or its resolver.
	 *
	 * @param string $name
	 * @return Elgg_Di_Container
	 * @throws InvalidArgumentException
	 */
	public function remove($name) {
		if ($name[0] === '_') {
			throw new InvalidArgumentException('Name cannot begin with underscore');
		}
		if (property_exists($this, $name)) {
			unset($this->{$name});
		} else {
			$this->_core->remove($name);
		}
		return $this;
	}
}
