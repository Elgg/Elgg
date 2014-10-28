<?php

/**
 * Container holding values which can be resolved upon reading and optionally stored and shared
 * across reads.
 *
 * <code>
 * $c = new Elgg_Di_DiContainer();
 *
 * $c->setFactory('foo', 'Foo_factory'); // $c will be passed to Foo_factory()
 * $c->foo; // new Foo instance
 * $c->foo; // same instance
 *
 * $c->setFactory('bar', 'Bar_factory', false); // non-shared
 * $c->bar; // new Bar instance
 * $c->bar; // different Bar instance
 *
 * $c->setValue('a_string', 'foo_factory'); // don't call this
 * $c->a_string; // 'foo_factory'
 * </code>
 *
 * @access private
 *
 * @package Elgg.Core
 * @since   1.9
 */
class Elgg_Di_DiContainer {

	/**
	 * @var array each element is an array: ['callable' => mixed $factory, 'shared' => bool $isShared]
	 */
	protected $factories = array();

	/**
	 * @var array
	 */
	protected $cache = array();

	const CLASS_NAME_PATTERN_52 = '/^[a-z_\x7f-\xff][a-z0-9_\x7f-\xff]*$/i';
	const CLASS_NAME_PATTERN_53 = '/^(\\\\?[a-z_\x7f-\xff][a-z0-9_\x7f-\xff]*)+$/i';

	/**
	 * Fetch a value.
	 *
	 * @param string $name The name of the value to fetch
	 * @return mixed
	 * @throws Elgg_Di_MissingValueException
	 */
	public function __get($name) {
		if (array_key_exists($name, $this->cache)) {
			return $this->cache[$name];
		}
		if (!isset($this->factories[$name])) {
			throw new Elgg_Di_MissingValueException("Value or factory was not set for: $name");
		}
		$value = $this->build($this->factories[$name]['callable'], $name);

		// Why check existence of factory here? A: the builder function may have set the value
		// directly, in which case the factory will no longer exist.
		if (!empty($this->factories[$name]) && $this->factories[$name]['shared']) {
			$this->cache[$name] = $value;
		}
		return $value;
	}

	/**
	 * Build a value
	 *
	 * @param mixed  $factory The factory for the value
	 * @param string $name    The name of the value
	 * @return mixed
	 * @throws Elgg_Di_FactoryUncallableException
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
		throw new Elgg_Di_FactoryUncallableException($msg);
	}

	/**
	 * Set a value to be returned without modification
	 *
	 * @param string $name  The name of the value
	 * @param mixed  $value The value
	 * @return Elgg_Di_DiContainer
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
	 * @param string   $name     The name of the value
	 * @param callable $callable Factory for the value
	 * @param bool     $shared   Whether the same value should be returned for every request
	 * @return Elgg_Di_DiContainer
	 * @throws InvalidArgumentException
	 */
	public function setFactory($name, $callable, $shared = true) {
		if (!is_callable($callable, true)) {
			throw new InvalidArgumentException('$factory must appear callable');
		}
		$this->remove($name);
		$this->factories[$name] = array(
			'callable' => $callable,
			'shared' => $shared
		);
		return $this;
	}

	/**
	 * Set a factory based on instantiating a class with no arguments.
	 *
	 * @param string $name       Name of the value
	 * @param string $class_name Class name to be instantiated
	 * @param bool   $shared     Whether the same value should be returned for every request
	 * @return Elgg_Di_DiContainer
	 * @throws InvalidArgumentException
	 */
	public function setClassName($name, $class_name, $shared = true) {
		$classname_pattern = version_compare(PHP_VERSION, '5.3', '<') ? self::CLASS_NAME_PATTERN_52 : self::CLASS_NAME_PATTERN_53;
		if (!is_string($class_name) || !preg_match($classname_pattern, $class_name)) {
			throw new InvalidArgumentException('Class names must be valid PHP class names');
		}
		$func = create_function('', "return new $class_name();");
		return $this->setFactory($name, $func, $shared);
	}

	/**
	 * Remove a value from the container
	 *
	 * @param string $name The name of the value
	 * @return Elgg_Di_DiContainer
	 */
	public function remove($name) {
		unset($this->cache[$name]);
		unset($this->factories[$name]);
		return $this;
	}

	/**
	 * Does the container have this value
	 *
	 * @param string $name The name of the value
	 * @return bool
	 */
	public function has($name) {
		return isset($this->factories[$name]) || array_key_exists($name, $this->cache);
	}
}
