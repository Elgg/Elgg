<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Represents an HTTP request.
 *
 * This will likely be replaced by Symfony's Request class from HttpFoundation
 * in the future.
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

		$this->initialize();
	}

	/**
	 * Creates a request from PHP's globals
	 *
	 * @return Elgg_Request
	 */
	public static function createFromGlobals() {
		return new Elgg_Request($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
	}

	/**
	 * Initialize the request
	 * 
	 * @return void
	 */
	protected function initialize() {
		$this->stripSlashesIfMagicQuotes();
	}

	/**
	 * Strip slashes if magic quotes is on
	 *
	 * @return void
	 */
	protected function stripSlashesIfMagicQuotes() {
		if (get_magic_quotes_gpc()) {
			$this->query = _elgg_stripslashes_deep($this->query);
			$this->request = _elgg_stripslashes_deep($this->request);
			$this->cookies = _elgg_stripslashes_deep($this->cookies);
			$this->server = _elgg_stripslashes_deep($this->server);
		}
	}

	/**
	 * Get a parameter from this request
	 *
	 * It is better to get parameters from the appropriate public property
	 * (query, request).
	 *
	 * @param string $key     The key
	 * @param mixed  $default The default value
	 * @return mixed
	 */
	public function get($key, $default = null) {
		if (isset($this->query[$key])) {
			return $this->query[$key];
		} else if (isset($this->request[$key])) {
			return $this->request[$key];
		} else {
			return $default;
		}
	}

	/**
	 * Get the IP address of the client
	 *
	 * @return string
	 */
	public function getClientIp() {
		if (isset($this->server['REMOTE_ADDR'])) {
			return $this->server['REMOTE_ADDR'];
		} else {
			return '';
		}
	}

	/**
	 * Is this an ajax request
	 *
	 * @return bool
	 */
	public function isXmlHttpRequest() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
			&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ||
			$this->get('X-Requested-With') === 'XMLHttpRequest';
	}
}
