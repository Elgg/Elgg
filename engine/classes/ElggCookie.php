<?php
/**
 * A simple object model for an HTTP cookie
 *
 * @package    Elgg.Core
 * @subpackage Http
 * @see        elgg_set_cookie()
 * @see        http://php.net/manual/en/function.setcookie.php
 * @see        http://php.net/manual/en/function.session-set-cookie-params.php
 * @since      1.9.0
 *
 * @property-read string $name Name of the cookie
 */
class ElggCookie {
	/** @var string */
	private $name;
	
	/** @var string */
	public $value = "";
	
	/** @var int */
	public $expire = 0;
	
	/** @var string */
	public $path = "/";
	
	/** @var string */
	public $domain = "";
	
	/** @var bool */
	public $secure = false;
	
	/** @var bool */
	public $httpOnly = false;
	
	/**
	 * Constructor
	 *
	 * @param string $name The name of the cookie.
	 */
	public function __construct($name) {
		$this->name = $name;
	}

	/**
	 * Get an attribute
	 *
	 * @param string $name Attribute name
	 * @return mixed
	 */
	public function __get($name) {
		// allow reading the private name attribute
		if ($name === 'name') {
			return $this->name;
		}
	}

	/**
	 * Set the time the cookie expires
	 *
	 * Example: $cookie->setExpiresTime("+30 days");
	 *
	 * @param string $time A time string appropriate for strtotime()
	 * @return void
	 */
	public function setExpiresTime($time) {
		$this->expire = strtotime($time);
	}
}
