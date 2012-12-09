<?php

/**
 * Value store used by the container. Can resolve values upon reading, as well as store whether each value
 * should be cached by the container.
 *
 * @access private
 */
class Elgg_Di_Core {

	/**
	 * @var array
	 */
	private $values = array();

	/**
	 * @var bool[]
	 */
	private $shared = array();

	/**
	 * @param $name
	 * @param Elgg_Di_Container $container
	 * @return mixed
	 * @throws Elgg_Di_Exception_MissingValueException
	 */
	public function get($name, Elgg_Di_Container $container) {
		if (!$this->has($name)) {
			throw new Elgg_Di_Exception_MissingValueException("Missing value: $name");
		}
		$value = $this->values[$name];
		if ($value instanceof Elgg_Di_ResolvableInterface) {
			/* @var Elgg_Di_ResolvableInterface $value */
			$value = $value->resolveValue($container);
		}
		return $value;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @param bool $share
	 * @return Elgg_Di_Core
	 */
	public function set($name, $value, $share = false) {
		$this->values[$name] = $value;
		if ($share) {
			$this->shared[$name] = true;
		}
		return $this;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function has($name) {
		return array_key_exists($name, $this->values);
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function isShared($name) {
		return isset($this->shared[$name]);
	}

	/**
	 * @param string $name
	 * @return Elgg_Di_Core
	 */
	public function remove($name) {
		unset($this->values[$name]);
		unset($this->shared[$name]);
		return $this;
	}
}
