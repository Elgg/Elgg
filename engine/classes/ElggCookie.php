<?php
/**
 * A simple object model for an HTTP cookie
 *
 * @see elgg_set_cookie()
 * @see http://php.net/manual/en/function.setcookie.php
 * @see http://php.net/manual/en/function.session-set-cookie-params.php
 * @since 1.9
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
	public $httponly = false;
	
	/**
	 * @param string $name The name of the cookie.
	 */
	public function __construct($name) {
		$this->name = $name;
	}
	
	public function __get($name) {
		// Make the name field readonly
		if ($name === 'name') {
			return $this->name;
		}
	}

	/**
	 * Set the expire time for the cookie
	 *
	 * Example: $cookie->setExpire("+30 days");
	 *
	 * @param string $time A time string appropriate for strtotime()
	 */
	public function setExpire($time) {
		$this->expire = strtotime($time);
	}
}