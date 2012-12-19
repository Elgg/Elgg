<?php

/**
 * Container holding values which can be resolved upon reading and optionally stored and shared
 * across reads.
 *
 * <code>
 * $c = new Elgg_DIContainer();
 *
 * $c->setFactory('foo', 'Foo_factory'); // $c will be passed to Foo_factory()
 * $c->get('foo'); // new Foo instance
 * $c->get('foo'); // same instance
 *
 * $c->setFactory('bar', 'Bar_factory', false); // non-shared
 * $c->get('bar'); // new Bar instance
 * $c->get('bar'); // different Bar instance
 *
 * $c->setValue('a_string', 'foo_factory'); // don't call this
 * $c->get('a_string'); // 'foo_factory'
 * </code>
 *
 * @access private
 */
class Elgg_DIContainer {

	/**
	 * @var array each element is an array: [0 => mixed $factory, 1=> bool $isShared]
	 */
	protected $factories = array();

	/**
	 * @var array
	 */
	protected $cache = array();

	/**
	 * Fetch a value.
	 *
	 * @param string $name
	 * @return mixed
	 * @throws Elgg_DIContainer_MissingValueException
	 */
	public function get($name) {
		if (array_key_exists($name, $this->cache)) {
			return $this->cache[$name];
		}
		if (!isset($this->factories[$name])) {
			throw new Elgg_DIContainer_MissingValueException("Value or factory was not set for: $name");
		}
		$value = $this->build($this->factories[$name][0], $name);
		if ($this->factories[$name][1]) {
			$this->cache[$name] = $value;
		}
		return $value;
	}

	/**
	 * @param mixed $factory
	 * @param string $name
	 * @return mixed
	 * @throws Elgg_DIContainer_FactoryUncallableException
	 */
	protected function build($factory, $name) {
		if (is_callable($factory)) {
			return call_user_func($factory, $this);
		}
		$msg = "Factory for '$name' was uncallable";
		if (is_string($factory)) {
			$msg .= ": '$factory'";
		} elseif (is_array($factory)) {
			if (is_string($factory[0])) {
				$msg .= ": '{$factory[0]}::{$factory[1]}'";
			} else {
				$msg .= ": " . get_class($factory[0]) . "->{$factory[1]}";
			}
		}
		throw new Elgg_DIContainer_FactoryUncallableException($msg);
	}

	/**
	 * Set a value to be returned without modification
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return Elgg_DIContainer
	 * @throws InvalidArgumentException
	 */
	public function setValue($name, $value) {
		$this->remove($name);
		$this->cache[$name] = $value;
		return $this;
	}

	/**
	 * Set a factory to generate a value when the container is read.
	 *
	 * @param string $name
	 * @param callable $factory
	 * @param bool $shared
	 * @return Elgg_DIContainer
	 * @throws InvalidArgumentException
	 */
	public function setFactory($name, $factory, $shared = true) {
		if (!is_callable($factory, true)) {
			throw new InvalidArgumentException('$value must appear callable');
		}
		$this->remove($name);
		$this->factories[$name] = array($factory, $shared);
		return $this;
	}

	/**
	 * @param string $name
	 * @return Elgg_DIContainer
	 */
	public function remove($name) {
		unset($this->cache[$name]);
		unset($this->factories[$name]);
		return $this;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function has($name) {
		return isset($this->factories[$name]) || array_key_exists($name, $this->cache);
	}
}
