<?php
/**
 * Wrap an object and display warnings whenever the object's variables are
 * accessed or a method is used. It can also be used to wrap a string.
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


	/**
	 * Create the wrapper
	 * @param mixed  $object  The object or string to wrap
	 * @param string $message The deprecation message to display when used
	 * @param float  $version The Elgg version this was deprecated
	 */
	public function __construct($object, $message, $version) {
		if (is_object($object)) {
			$this->object = $object;
		} else {
			$this->string = $object;
		}
		$this->message = $message;
		$this->version = $version;
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
		if (isset($this->string)) {
			$this->displayWarning();
			return $this->string;
		}
	}

	protected function displayWarning() {
		// display 2 levels in the function stack to get back to original use
		elgg_deprecated_notice($this->message, $this->version, 2);
	}
}
