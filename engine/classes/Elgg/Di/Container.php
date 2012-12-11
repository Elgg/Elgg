<?php

/**
 * Container holding values which can be resolved upon reading and optionally stored and shared
 * across reads.
 *
 * Values are read as properties, but must be set via set().
 *
 * <code>
 * $c = new Elgg_Di_Container();
 *
 * // non-shared value from a factory
 * $c->set('foo', new Elgg_Di_Factory('Foo'));
 *
 * // shared value returned from a callable function
 * $c->set('bar', new Elgg_Di_Invoker('get_new_bar'), true);
 *
 * $c->foo; // new Foo instance created
 * $c->foo; // separate Foo instance created
 *
 * $c->bar; // Bar instance cached as new property
 * $c->bar; // simply reading the property
 *
 * // a reference lets you read from the container at resolve-time
 * $c->set('barAlias', new Elgg_Di_Reference('bar'));
 *
 * $c->barAlias; // returns $c->bar
 * </code>
 *
 * @access private
 */
class Elgg_Di_Container {

	/**
	 * @var Elgg_Di_ResolvableInterface[]
	 */
	protected $_resolvables = array();

	/**
	 * @var bool[]
	 */
	private $_shared = array();

	/**
	 * Fetch a value that must be resolved. If the resolved value can be shared, it is placed
	 * as a dynamic property on the container.
	 *
	 * @param string $name
	 * @return mixed
	 * @throws Elgg_Di_Exception_MissingValueException
	 */
	public function __get($name) {
		if (!isset($this->_resolvables[$name])) {
			throw new Elgg_Di_Exception_MissingValueException("Missing value: $name");
		}
		$value = $this->_resolvables[$name]->resolveValue($this);
		if (isset($this->_shared[$name])) {
			// cache in property
			$this->{$name} = $value;
			unset($this->_resolvables[$name]);
			unset($this->_shared[$name]);
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
		return property_exists($this, $name) || isset($this->_resolvables[$name]);
	}

	/**
	 * Will reads return the same value?
	 *
	 * @param string $name
	 * @return bool
	 */
	public function isShared($name) {
		return property_exists($this, $name) || isset($this->_shared[$name]);
	}

	/**
	 * @param string $name
	 * @return Elgg_Di_Container
	 */
	public function makeShared($name) {
		if (isset($this->_resolvables[$name])) {
			$this->_shared[$name] = true;
		}
		return $this;
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
			$this->_resolvables[$name] = $value;
			if ($share) {
				$this->_shared[$name] = true;
			}
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
			unset($this->_resolvables[$name]);
			unset($this->_shared[$name]);
		}
		return $this;
	}
}
