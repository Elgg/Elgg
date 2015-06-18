<?php

namespace Elgg\Views;

/**
 * Corresponds to a different way to transmit the data for the given page.
 * Could be:
 * 
 *  * "default" (html)
 *  * "json"
 *  * "mobile" (mobile-optimized html),
 *  * "tv" (tv-optimized html)
 *  * "rss"
 * 
 * @since 2.0.0
 * @access private
 */
class Viewtype {
	
	/** @var string */
	private $name;
	
	/** @var Viewtype */
	private $fallback;
	
	/**
	 * Constructor
	 * 
	 * @param string $name The string representation of the viewtype.
	 */
	private function __construct(/*string*/ $name) {
		$this->name = $name;
	}
	
	/**
	 * Configure a fallback for this viewtype.
	 * 
	 * @param Viewtype $fallback The viewtype to fall back to
	 * 
	 * @return void
	 */
	public function setFallback(Viewtype $fallback = null) {
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
	
	/**
	 * Returns the name of this viewtype.
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Instantiate a new viewtype, ensuring that it is valid.
	 * 
	 * @param string $name The name of the new viewtype.
	 * 
	 * @return Viewtype
	 */
	public static function create(/*string*/ $name) {
		if (!Viewtype::isValid($name)) {
			throw new \InvalidArgumentException("$name is not a valid viewtype");
		}
		
		return new Viewtype($name);
	}
	
	/**
	 * Checks if $viewtype is a string suitable for use as a viewtype name
	 *
	 * @param string $name Potential viewtype name. Alphanumeric chars plus _ allowed.
	 *
	 * @return bool
	 * @since 1.9
	 */
	public static function isValid(/*string*/ $name) {
		return is_string($name) &&
			$name !== '' &&
			!preg_match('/\W/', $name);
	}
}