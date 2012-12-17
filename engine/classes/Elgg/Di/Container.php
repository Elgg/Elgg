<?php

/**
 * Container holding values which can be resolved upon reading and optionally stored and shared
 * across reads.
 *
 * Values are read as properties, but must be set via set().
 *
 * <code>
 * $c = new Elgg_Di_Container();
 * $c->setFactory('foo', new Elgg_Di_Factory('Foo'), true);
 * $c->setFactory('bar', new Elgg_Di_Invoker('get_new_bar'));
 *
 * $c->get('foo');
 * c->get('foo'); // same instance
 *
 * $c->get('bar'); // new instance every time
 *
 * // a reference lets you read from the container at resolve-time
 * $c->setFactory('barAlias', $c->ref('bar'));
 *
 * $c->get('barAlias'); // returns $c->get('bar')
 * </code>
 *
 * @access private
 */
class Elgg_Di_Container {

	/**
	 * @var Elgg_Di_FactoryInterface[]
	 */
	protected $factories = array();

	/**
	 * @var array
	 */
	protected $cache = array();

	/**
	 * @var bool[]
	 */
	protected $shared = array();

	/**
	 * Fetch a value.
	 *
	 * @param string $name
	 * @return mixed
	 * @throws Elgg_Di_Exception_MissingValueException
	 */
	public function get($name) {
		if (array_key_exists($name, $this->cache)) {
			return $this->cache[$name];
		}
		if (!isset($this->factories[$name])) {
			throw new Elgg_Di_Exception_MissingValueException("Missing value: $name");
		}
		$value = $this->factories[$name]->createValue($this);
		if (!empty($this->shared[$name])) {
			$this->cache[$name] = $value;
		}
		return $value;
	}

	/**
	 * Set a static value or object.
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return Elgg_Di_Container
	 * @throws InvalidArgumentException
	 */
	public function setValue($name, $value) {
		if ($value instanceof Elgg_Di_FactoryInterface) {
			throw new InvalidArgumentException('Cannot set a factory as a value');
		}
		$this->remove($name);
		$this->cache[$name] = $value;
		return $this;
	}

	/**
	 * Set a factory to generate a value when the container is read.
	 *
	 * @param string $name
	 * @param Elgg_Di_FactoryInterface $value
	 * @param bool $shared
	 * @return Elgg_Di_Container
	 */
	public function setFactory($name, Elgg_Di_FactoryInterface $value, $shared = false) {
		$this->remove($name);
		$this->factories[$name] = $value;
		if ($shared) {
			$this->shared[$name] = true;
		}
		return $this;
	}

	/**
	 * @param string $name
	 * @return Elgg_Di_Container
	 */
	public function remove($name) {
		unset($this->cache[$name]);
		unset($this->factories[$name]);
		unset($this->shared[$name]);
		return $this;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function has($name) {
		return isset($this->factories[$name]) || array_key_exists($name, $this->cache);
	}

	/**
	 * Helper to get a reference to a value in a container.
	 *
	 * @param string $name
	 * @return Elgg_Di_Reference
	 */
	public function ref($name) {
		return new Elgg_Di_Reference($name);
	}
}
