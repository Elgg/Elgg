<?php

namespace Elgg\Views;

/**
 * @access private
 */
class Viewtype {
	
	/** @var string */
	private $value;
	
	/** @var Viewtype */
	private $fallback;
	
	/**
	 * Constructor
	 * 
	 * @param string $value The string representation of the viewtype.
	 */
	private function __construct($value) {
		$this->value = $value;
	}
	
	/**
	 * Configure a fallback for this viewtype.
	 * 
	 * @param Viewtype $fallback The viewtype to fall back to.
	 */
	public function setFallback(Viewtype $fallback) {
		$this->fallback = $fallback;
	}
	
	/**
	 * The fallback registered to this viewtype, or null if none.
	 * 
	 * @return Viewtype|null
	 */
	public function getFallback() {
		return $this->fallback;
	}
	
	/**
	 * Whether a fallback has been registered for this viewtype.
	 * 
	 * @return bool 
	 */
	public function hasFallback() {
		return isset($this->fallback);
	}
	
	/** @inheritDoc */
	public function __toString() {
		return $this->value;
	}
	
	/**
	 * Instantiate a new viewtype, ensuring that it is valid.
	 * 
	 * @return Viewtype
	 */
	public static function create($viewtype) {
		if (!Viewtype::isValid($viewtype)) {
			throw new \InvalidArgumentException("$viewtype is not a valid viewtype");
		}
		
		return new Viewtype($viewtype);
	}
	
	/**
	 * Checks if $viewtype is a string suitable for use as a viewtype name
	 *
	 * @param string $viewtype Potential viewtype name. Alphanumeric chars plus _ allowed.
	 *
	 * @return bool
	 * @since 1.9
	 */
	public static function isValid($viewtype) {
		return is_string($viewtype) &&
			$viewtype !== '' &&
			!preg_match('/\W/', $viewtype);
	}
}