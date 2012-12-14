<?php

/**
 * Object that builds an object instance to return it as a value.
 *
 * Resolvable objects such as Elgg_Di_Reference can be used to determine the classname,
 * constructor arguments, or values to be passed to setter methods.
 *
 * <code>
 * // This factory, when resolved, will create a Pizza object, passing in two arguments,
 * // the second of which is pulled from the container. Before the pizza is returned,
 * // the method "setDough" is called and passed the container's value for "dough".
 * $factory = new Elgg_Di_Factory('Pizza', array(
 *     'deluxe',
 *     new Elgg_Di_Reference('cheese'),
 * ));
 * $factory->addMethodCall('setDough', new Elgg_Di_Reference('dough'));
 * $container->set('pizza', $factory);
 * </code>
 *
 * @access private
 */
class Elgg_Di_Factory implements Elgg_Di_ResolvableInterface {

	protected $class;
	protected $arguments;
	protected $container;
	protected $plan = array();

	/**
	 * @param string|Elgg_Di_ResolvableInterface $class
	 * @param array $constructorArguments
	 */
	public function __construct($class, array $constructorArguments = array()) {
		$this->class = $class;
		$this->arguments = array_values($constructorArguments);
	}

	/**
	 * Set an argument for the constructor
	 *
	 * @param int $index
	 * @param mixed $value
	 * @return Elgg_Di_Factory
	 * @throws InvalidArgumentException
	 */
	public function setConstructorArgument($index, $value) {
		if (((int)$index != $index) || $index < 0) {
			throw new InvalidArgumentException('index must be a non-negative integer');
		}
		if ($index >= count($this->arguments)) {
			$this->arguments = array_pad($this->arguments, $index + 1, null);
		}
		$this->arguments[$index] = $value;
		return $this;
	}

	/**
	 * Prepare a setter method to be called on the constructed object before being returned. A reference
	 * object can be used to have the value pulled from the container at read-time.
	 *
	 * @param string $method
	 * @param mixed $value a value or a value which can be resolved at read-time
	 * @return Elgg_Di_Factory
	 */
	public function addMethodCall($method, $value) {
		$this->plan[] = array('setter', $method, $value);
		return $this;
	}

	/**
	 * Prepare a property to be set on the constructed object before being returned. A reference
	 * object can be used to have the value pulled from the container at read-time.
	 *
	 * @param string $property
	 * @param mixed $value a value or a value which can be resolved at read-time
	 * @return Elgg_Di_Factory
	 */
	public function addPropertySet($property, $value) {
		$this->plan[] = array('prop', $property, $value);
		return $this;
	}

	/**
	 * @param Elgg_Di_Container $container
	 * @return object
	 * @throws ErrorException
	 */
	public function resolveValue(Elgg_Di_Container $container) {
		$this->container = $container;

		$class = $this->_resolve($this->class);
		if (!is_string($class)) {
			throw new ErrorException('Resolved class name was not a string');
		}
		if (!class_exists($class)) {
			throw new ErrorException("Class was not defined and failed to autoload: $class");
		}

		if (empty($this->arguments)) {
			$obj = new $class();
		} else {
			$arguments = array_values($this->arguments);
			$arguments = array_map(array($this, '_resolve'), $arguments);
			$ref = new ReflectionClass($class);
			$obj = $ref->newInstanceArgs($arguments);
		}

		foreach ($this->plan as $step) {
			list($type, $name, $value) = $step;
			if ($type === 'setter') {
				$obj->{$name}($this->_resolve($value));
			} else {
				$obj->{$name} = $this->_resolve($value);
			}
		}

		// don't want to keep a reference to the container
		$this->container = null;

		return $obj;
	}

	/**
	 * @param mixed $value
	 * @return mixed
	 */
	protected function _resolve($value) {
		if ($value instanceof Elgg_Di_ResolvableInterface) {
			/* @var Elgg_Di_ResolvableInterface $value */
			$value = $value->resolveValue($this->container);
		}
		return $value;
	}
}
