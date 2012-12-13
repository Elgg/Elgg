<?php

/**
 * Container holding values which can be resolved upon reading and optionally stored and shared
 * across reads.
 *
 * Values are read as properties, but must be set via set().
 *
 * <code>
 * $c = new Elgg_Di_Container();
 * $c->set('foo', new Elgg_Di_Factory('Foo'));
 * $c->set('bar', new Elgg_Di_Invoker('get_new_bar'));
 *
 * $c->foo; // new Foo instance created and stored in property
 * $c->foo; // property read (same instance)
 *
 * $c->new_foo(); // new instance every time
 *
 * // a reference lets you read from the container at resolve-time
 * $c->set('barAlias', $c->ref('bar'));
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
	 * Fetch a value.
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
		// cache in property
		$this->{$name} = $value;
		return $value;
	}

	/**
	 * Fetch a freshly-resolved value.
	 *
	 * @param string $method method name must start with "new_"
	 * @param array $args
	 * @return mixed
	 * @throws Elgg_Di_Exception_ValueUnresolvableException
	 * @throws BadMethodCallException
	 */
	public function __call($method, $args) {
		if (0 !== strpos($method, 'new_')) {
			throw new BadMethodCallException("Method name must begin with 'new_'");
		}
		$name = substr($method, 4);
		if (!isset($this->_resolvables[$name])) {
			throw new Elgg_Di_Exception_ValueUnresolvableException("Unresolvable value: $name");
		}
		return $this->_resolvables[$name]->resolveValue($this);
	}

	/**
	 * Does the container have this value or know how to retrieve it?
	 *
	 * @param string $name
	 * @return bool
	 */
	public function has($name) {
		return (isset($this->_resolvables[$name]) || property_exists($this, $name));
	}

	/**
	 * Can we fetch a new value via new_$name()?
	 *
	 * @param string $name
	 * @return bool
	 */
	public function isResolvable($name) {
		return isset($this->_resolvables[$name]);
	}

	/**
	 * Store a value or an object that can be resolved to a value.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return Elgg_Di_Container
	 * @throws InvalidArgumentException
	 */
	public function set($name, $value) {
		if ($name[0] === '_') {
			throw new InvalidArgumentException('Name cannot begin with underscore');
		}
		$this->remove($name);
		if ($value instanceof Elgg_Di_ResolvableInterface) {
			$this->_resolvables[$name] = $value;
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
		}
		return $this;
	}

	/**
	 * Helper to get a reference to a value in a container.
	 *
	 * @param string $name
	 * @param bool $bound if given as true, the reference will always fetch from this container
	 * @return Elgg_Di_Reference
	 *
	 * @note This function creates unbound refs by default, so that, in the future, if references need to be
	 *       serialized, they will not have refs to the container
	 */
	public function ref($name, $bound = false) {
		$cont = $bound ? $this : null;
		return new Elgg_Di_Reference($name, $cont);
	}
}
