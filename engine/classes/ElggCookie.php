<?php
/**
 * A simple object model for an HTTP cookie
 * 
 * @see http://php.net/manual/en/function.setcookie.php
 * @see http://php.net/manual/en/function.session-set-cookie-params.php
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
	function __construct($name) {
		$this->name = $name;
	}
	
	function __get($name) {
		// Make the name field readonly
		if ($name === 'name') {
			return $this->name;
		}
	}
}