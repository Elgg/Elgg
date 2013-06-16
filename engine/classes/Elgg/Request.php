<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Represents an HTTP request.
 * 
 * @package    Elgg.Core
 * @subpackage Http
 * @since      1.9.0
 * @access private
 */
class Elgg_Request {
	public $query;
	public $request;
	public $cookies;
	public $files;
	public $server;

	/**
	 * Create a request
	 *
	 * @param array $query   GET parameters
	 * @param array $request POST parameters
	 * @param array $cookies COOKIE parameters
	 * @param array $files   FILES parameters
	 * @param array $server  SERVER parameters
	 */
	public function __construct(array $query = array(), array $request = array(),
			array $cookies = array(), array $files = array(), array $server = array()) {
		$this->query = $query;
		$this->request = $request;
		$this->cookies = $cookies;
		$this->files = $files;
		$this->server = $server;
	}

	/**
	 * Creates a request from PHP's globals
	 *
	 * @return Elgg_Request
	 */
	public static function createFromGlobals() {
		return new Elgg_Request($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
	}
}
