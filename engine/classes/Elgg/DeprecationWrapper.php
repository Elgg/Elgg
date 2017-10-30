<?php
namespace Elgg;
/**
 * Wrap an object and display warnings whenever the object's variables are
 * accessed or a method is used. It can also be used to wrap a string.
 *
 * Note that the wrapper will not share the type of the wrapped object and will
 * fail type hints, instanceof, etc.
 *
 * This was introduced for deprecating passing particular variables to views
 * automatically in elgg_view().
 * It can be removed once that use is no longer required.
 *
 * Wraps:
 *  url string in ViewsService
 *  config object in ViewsService
 *  user object in ViewsService
 *  session object in session lib
 *  config object in ElggPlugin::includeFile
 *
 * @access private
 *
 * @package Elgg.Core
 */
class DeprecationWrapper implements \ArrayAccess {
	/** @var object */
	protected $object;

	/** @var string */
	protected $string;

	/** @var string */
	protected $message;

	/** @var string */
	protected $version;

	/** @var callable */
	protected $reporter;

	/**
	 * Create the wrapper
	 *
	 * @param mixed    $object   The object or string to wrap
	 * @param string   $message  The deprecation message to display when used
	 * @param string   $version  The Elgg version this was deprecated
	 * @param callable $reporter function called to report deprecation
	 */
	public function __construct($object, $message, $version, $reporter = 'elgg_deprecated_notice') {
		if (is_object($object)) {
			$this->object = $object;
		} else {
			$this->string = $object;
		}
		$this->message = $message;
		$this->version = $version;
		$this->reporter = $reporter;
	}

	/**
	 * Get a property on the object
	 *
	 * @param string $name Property name
	 * @return mixed
	 */
	public function __get($name) {
		$this->displayWarning();
		return $this->object->$name;
	}

	/**
	 * Set a property on the object
	 *
	 * @param string $name  Property name
	 * @param mixed  $value Property value
	 * @return void
	 */
	public function __set($name, $value) {
		$this->displayWarning();
		$this->object->$name = $value;
	}

	/**
	 * Is a property set?
	 *
	 * @param string $name Property name
	 * @return bool
	 */
	public function __isset($name) {
		$this->displayWarning();
		return isset($this->object->$name);
	}

	/**
	 * Call a method on the object
	 *
	 * @param string $name      Method name
	 * @param array  $arguments Method arguments
	 * @return mixed
	 */
	public function __call($name, $arguments) {
		$this->displayWarning();
		return call_user_func_array([$this->object, $name], $arguments);
	}

	/**
	 * Get the object as string
	 *
	 * @return string
	 */
	public function __toString() {
		$this->displayWarning();
		if (isset($this->string)) {
			return $this->string;
		} else {
			return (string) $this->object;
		}
	}

	/**
	 * Display a warning
	 *
	 * @return void
	 */
	protected function displayWarning() {
		// display 3 levels in the function stack to get back to original use
		// 1 for __get/__call/__toString()
		// 1 for displayWarning()
		// 1 for call_user_func()
		call_user_func($this->reporter, $this->message, $this->version, 3);
	}

	/**
	 * Array access interface
	 *
	 * @see \ArrayAccess::offsetSet()
	 *
	 * @param mixed $key   Name
	 * @param mixed $value Value
	 *
	 * @return void
	 */
	public function offsetSet($key, $value) {
		$this->displayWarning();
		if (is_object($this->object) && !$this->object instanceof \ArrayAccess) {
			$this->object->$key = $value;
		} else {
			if ($key === null) {
				// Yes this is necessary. Otherwise $key will be interpreted as empty string
				$this->object[] = $value;
			} else {
				$this->object[$key] = $value;
			}
		}
	}

	/**
	 * Array access interface
	 *
	 * @see \ArrayAccess::offsetGet()
	 *
	 * @param mixed $key Name
	 *
	 * @return mixed
	 */
	public function offsetGet($key) {
		$this->displayWarning();
		if (is_object($this->object) && !$this->object instanceof \ArrayAccess) {
			return $this->object->$key;
		} else {
			return $this->object[$key];
		}
	}

	/**
	 * Array access interface
	 *
	 * @see \ArrayAccess::offsetUnset()
	 *
	 * @param mixed $key Name
	 *
	 * @return void
	 */
	public function offsetUnset($key) {
		$this->displayWarning();
		if (is_object($this->object) && !$this->object instanceof \ArrayAccess) {
			unset($this->object->$key);
		} else {
			unset($this->object[$key]);
		}
	}

	/**
	 * Array access interface
	 *
	 * @see \ArrayAccess::offsetExists()
	 *
	 * @param mixed $offset Offset
	 *
	 * @return bool
	 */
	public function offsetExists($offset) {
		$this->displayWarning();
		if (is_object($this->object) && !$this->object instanceof \ArrayAccess) {
			return isset($this->object->$offset);
		} else {
			return array_key_exists($offset, $this->object);
		}
	}
}

