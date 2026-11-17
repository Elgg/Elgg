<?php
/**
 * A simple object model for an HTTP cookie
 *
 * @see        elgg_set_cookie()
 * @see        http://php.net/manual/en/function.setcookie.php
 * @see        http://php.net/manual/en/function.session-set-cookie-params.php
 * @since      1.9.0
 *
 * @property-read string $name Name of the cookie
 */
class ElggCookie {
	
	public string $value = '';
	
	public int $expire = 0;
	
	public string $path = '/';
	
	public string $domain = '';
	
	public bool $secure = false;
	
	public bool $httpOnly = false;
	
	/**
	 * Constructor
	 *
	 * @param string $name The name of the cookie.
	 */
	public function __construct(protected string $name) {
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
	public function setExpiresTime($time): void {
		$this->expire = strtotime($time);
	}
}
