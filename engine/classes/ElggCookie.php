<?php
/**
 * A simple object model for an HTTP cookie
 */
class ElggCookie {
	/** @var string */
	private $name;
	
	/** @var string */
	public $value = "";
	
	/** @var int */
	public $expires = 0;
	
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