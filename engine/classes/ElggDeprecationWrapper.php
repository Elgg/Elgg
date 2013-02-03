<?php
/**
 * Wrap an object and display warnings whenever the object's variables are
 * accessed or a method is used. It can also be used to wrap a string.
 *
 * Note that the wrapper will not share the type of the wrapped object and will
 * fail type hints, instanceof, etc.
 *
 * This was introduced for deprecating passing particular variabled to views
 * automatically in elgg_view(). It can be removed once that use is no longer
 * required.
 *
 * @access private
 */
class ElggDeprecationWrapper {
	/** @var object */
	protected $object;

	/** @var string */
	protected $string;

	/** @var string */
	protected $message;

	/** @var float */
	protected $version;

	/** @var callable */
	protected $reporter;

	/**
	 * Create the wrapper
	 * @param mixed  $object  The object or string to wrap
	 * @param string $message The deprecation message to display when used
	 * @param float  $version The Elgg version this was deprecated
	 *
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
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		$this->displayWarning();
		return $this->object->$name;
	}

	/**
	 * Set a property on the object
	 * @param string $name
	 * @param mixed  $value
	 * @return void
	 */
	public function __set($name, $value) {
		$this->displayWarning();
		$this->object->$name = $value;
	}

	/**
	 * Call a method on the object
	 * @param string $name
	 * @param array  $arguments
	 * @return mixed
	 */
	public function __call($name, $arguments) {
		$this->displayWarning();
		return call_user_func_array(array($this->object, $name), $arguments);
	}

	/**
	 * Display the string
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

	protected function displayWarning() {
		// display 3 levels in the function stack to get back to original use
		// 1 for __get/__call/__toString()
		// 1 for displayWarning()
		// 1 for call_user_func()
		call_user_func($this->reporter, $this->message, $this->version, 3);
	}
}
