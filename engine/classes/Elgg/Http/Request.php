<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Represents an HTTP request.
 *
 * This will likely be replaced by Symfony's Request class from HttpFoundation
 * in the future.
 *
 * Some methods were pulled from Symfony. They are
 * Copyright (c) 2004-2013 Fabien Potencier
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package    Elgg.Core
 * @subpackage Http
 * @since      1.9.0
 * @access private
 */
class Elgg_Http_Request {
	/**
	 * @var Elgg_Http_ParameterBag GET parameters
	 */
	public $query;

	/**
	 *
	 * @var Elgg_Http_ParameterBag POST parameters
	 */
	public $request;

	/**
	 *
	 * @var Elgg_Http_ParameterBag COOKIE parameters
	 */
	public $cookies;

	/**
	 *
	 * @var Elgg_Http_ParameterBag FILES parameters
	 */
	public $files;

	/**
	 *
	 * @var Elgg_Http_ParameterBag SERVER parameters
	 */
	public $server;

	/**
	 *
	 * @var Elgg_Http_ParameterBag Header parameters
	 */
	public $headers;

	/**
	 * @var string
	 */
	protected $baseUrl = null;

	/**
	 * @var string
	 */
	protected $requestUri = null;

	/**
	 * @var string
	 */
	protected $pathInfo = null;

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
		$this->initialize($query, $request, $cookies, $files, $server);
	}

	/**
	 * Initialize the request
	 *
	 * @param array $query   GET parameters
	 * @param array $request POST parameters
	 * @param array $cookies COOKIE parameters
	 * @param array $files   FILES parameters
	 * @param array $server  SERVER parameters
	 * @return void
	 */
	protected function initialize(array $query = array(), array $request = array(),
			array $cookies = array(), array $files = array(), array $server = array()) {
		$this->query = new Elgg_Http_ParameterBag($this->stripSlashesIfMagicQuotes($query));
		$this->request = new Elgg_Http_ParameterBag($this->stripSlashesIfMagicQuotes($request));
		$this->cookies = new Elgg_Http_ParameterBag($this->stripSlashesIfMagicQuotes($cookies));
		// Symfony uses FileBag so this will change in next Elgg version
		$this->files = new Elgg_Http_ParameterBag($files);
		$this->server = new Elgg_Http_ParameterBag($server);

		$headers = $this->prepareHeaders();
		// Symfony uses HeaderBag so this will change in next Elgg version
		$this->headers = new Elgg_Http_ParameterBag($headers);
	}

	/**
	 * Creates a request from PHP's globals
	 *
	 * @return Elgg_Http_Request
	 */
	public static function createFromGlobals() {
		return new Elgg_Http_Request($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
	}

	/**
	 * Creates a Request based on a given URI and configuration.
	 *
	 * The information contained in the URI always take precedence
	 * over the other information (server and parameters).
	 *
	 * @param string $uri        The URI
	 * @param string $method     The HTTP method
	 * @param array  $parameters The query (GET) or request (POST) parameters
	 * @param array  $cookies    The request cookies ($_COOKIE)
	 * @param array  $files      The request files ($_FILES)
	 * @param array  $server     The server parameters ($_SERVER)
	 *
	 * @return Elgg_Http_Request
	 */
	public static function create($uri, $method = 'GET', $parameters = array(), $cookies = array(), $files = array(), $server = array()) {
		$server = array_merge(array(
			'SERVER_NAME' => 'localhost',
			'SERVER_PORT' => 80,
			'HTTP_HOST' => 'localhost',
			'HTTP_USER_AGENT' => 'Symfony/2.X',
			'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
			'HTTP_ACCEPT_LANGUAGE' => 'en-us,en;q=0.5',
			'HTTP_ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
			'REMOTE_ADDR' => '127.0.0.1',
			'SCRIPT_NAME' => '',
			'SCRIPT_FILENAME' => '',
			'SERVER_PROTOCOL' => 'HTTP/1.1',
			'REQUEST_TIME' => time(),
		), $server);

		$server['PATH_INFO'] = '';
		$server['REQUEST_METHOD'] = strtoupper($method);

		$components = parse_url($uri);
		if (isset($components['host'])) {
			$server['SERVER_NAME'] = $components['host'];
			$server['HTTP_HOST'] = $components['host'];
		}

		if (isset($components['scheme'])) {
			if ('https' === $components['scheme']) {
				$server['HTTPS'] = 'on';
				$server['SERVER_PORT'] = 443;
			} else {
				unset($server['HTTPS']);
				$server['SERVER_PORT'] = 80;
			}
		}

		if (isset($components['port'])) {
			$server['SERVER_PORT'] = $components['port'];
			$server['HTTP_HOST'] = $server['HTTP_HOST'] . ':' . $components['port'];
		}

		if (!isset($components['path'])) {
			$components['path'] = '/';
		}

		switch (strtoupper($method)) {
			case 'POST':
			case 'PUT':
			case 'DELETE':
				if (!isset($server['CONTENT_TYPE'])) {
					$server['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';
				}
			case 'PATCH':
				$request = $parameters;
				$query = array();
				break;
			default:
				$request = array();
				$query = $parameters;
				break;
		}

		if (isset($components['query'])) {
			parse_str(html_entity_decode($components['query']), $qs);
			// original uses array_replace. using array_merge for 5.2 BC
			$query = array_merge($qs, $query);
		}
		$queryString = http_build_query($query, '', '&');

		$server['REQUEST_URI'] = $components['path'] . ('' !== $queryString ? '?' . $queryString : '');
		$server['QUERY_STRING'] = $queryString;

		return new Elgg_Http_Request($query, $request, $cookies, $files, $server);
	}

	/**
	 * Normalizes a query string.
	 *
	 * It builds a normalized query string, where keys/value pairs are alphabetized,
	 * have consistent escaping and unneeded delimiters are removed.
	 *
	 * @param string $qs Query string
	 *
	 * @return string A normalized query string for the Request
	 */
	public static function normalizeQueryString($qs) {
		if ('' == $qs) {
			return '';
		}

		$parts = array();
		$order = array();

		foreach (explode('&', $qs) as $param) {
			if ('' === $param || '=' === $param[0]) {
				// Ignore useless delimiters, e.g. "x=y&".
				// Also ignore pairs with empty key, even if there was a value, e.g. "=value",
				// as such nameless values cannot be retrieved anyway.
				// PHP also does not include them when building _GET.
				continue;
			}

			$keyValuePair = explode('=', $param, 2);

			// GET parameters, that are submitted from a HTML form,
			// encode spaces as "+" by default (as defined in enctype application/x-www-form-urlencoded).
			// PHP also converts "+" to spaces when filling the global _GET or
			// when using the function parse_str. This is why we use urldecode and then normalize to
			// RFC 3986 with rawurlencode.
			$parts[] = isset($keyValuePair[1]) ?
					rawurlencode(urldecode($keyValuePair[0])) . '=' . rawurlencode(urldecode($keyValuePair[1])) :
					rawurlencode(urldecode($keyValuePair[0]));
			$order[] = urldecode($keyValuePair[0]);
		}

		array_multisort($order, SORT_ASC, $parts);

		return implode('&', $parts);
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
		if ($this->query->has($key)) {
			return $this->query->get($key);
		} else if ($this->request->has($key)) {
			return $this->request->get($key);
		} else {
			return $default;
		}
	}

	/**
	 * Returns the requested URI.
	 *
	 * @return string The raw URI (i.e. not urldecoded)
	 */
	public function getRequestUri() {
		if (null === $this->requestUri) {
			$this->requestUri = $this->prepareRequestUri();
		}

		return $this->requestUri;
	}

	/**
	 * Generates a normalized URI for the Request.
	 *
	 * @return string
	 */
	public function getUri() {
		if (null !== $qs = $this->getQueryString()) {
			$qs = '?' . $qs;
		}

		return $this->getSchemeAndHttpHost() . $this->getBaseUrl() . $this->getPathInfo() . $qs;
	}

	/**
	 * Gets the scheme and HTTP host.
	 *
	 * Includes port if non-standard.
	 *
	 * @return string The scheme and HTTP host
	 */
	public function getSchemeAndHttpHost() {
		return $this->getScheme() . '://' . $this->getHttpHost();
	}

	/**
	 * Gets the request's scheme.
	 *
	 * @return string
	 */
	public function getScheme() {
		return $this->isSecure() ? 'https' : 'http';
	}

	/**
	 * Returns the HTTP host being requested.
	 *
	 * The port name will be appended to the host if it's non-standard.
	 *
	 * @return string
	 */
	public function getHttpHost() {
		$scheme = $this->getScheme();
		$port = $this->getPort();

		if (('http' == $scheme && $port == 80) || ('https' == $scheme && $port == 443)) {
			return $this->getHost();
		}

		return $this->getHost() . ':' . $port;
	}

	/**
	 * Returns the host name.
	 *
	 * @return string
	 *
	 * @throws UnexpectedValueException when the host name is invalid
	 */
	public function getHost() {
		if (!$host = $this->headers->get('HOST')) {
			if (!$host = $this->server->get('SERVER_NAME')) {
				$host = $this->server->get('SERVER_ADDR', '');
			}
		}

		// trim and remove port number from host
		// host is lowercase as per RFC 952/2181
		$host = strtolower(preg_replace('/:\d+$/', '', trim($host)));

		// as the host can come from the user (HTTP_HOST and depending on the configuration,
		// SERVER_NAME too can come from the user)
		// check that it does not contain forbidden characters (see RFC 952 and RFC 2181)
		if ($host && !preg_match('/^\[?(?:[a-zA-Z0-9-:\]_]+\.?)+$/', $host)) {
			throw new UnexpectedValueException('Invalid Host');
		}

		return $host;
	}

	/**
	 * Is this request using SSL
	 *
	 * @return bool
	 */
	public function isSecure() {
		return 'on' == strtolower($this->server->get('HTTPS')) || 1 == $this->server->get('HTTPS');
	}

	/**
	 * Returns the port on which the request is made.
	 *
	 * @return string
	 */
	public function getPort() {
		return $this->server->get('SERVER_PORT');
	}

	/**
	 * Returns the root url from which this request is executed.
	 *
	 * The base URL never ends with a /. If the URL directly calls the
	 * executed script (e.g. index.php), this is included.
	 *
	 * Suppose Elgg is installed at http://localhost/mysite/ :
	 *
	 * * http://localhost/mysite/foo returns '/mysite'
	 * * http://localhost/mysite/index.php/foo returns '/mysite/index.php'
	 *
	 * @return string The raw url (i.e. not urldecoded)
	 */
	public function getBaseUrl() {
		if (null === $this->baseUrl) {
			$this->baseUrl = $this->prepareBaseUrl();
		}

		return $this->baseUrl;
	}

	/**
	 * Returns the path being requested relative to the executed script.
	 *
	 * The path info always starts with a /, does not contain the query string,
	 * and does not include the path to the Elgg installation.
	 *
	 * Suppose Elgg is installed at http://localhost/mysite/ :
	 *
	 * * http://localhost/mysite/ returns an empty string
	 * * http://localhost/mysite/about returns '/about'
	 * * http://localhost/mysite/enco%20ded returns '/enco%20ded'
	 * * http://localhost/mysite/about?var=1 returns '/about'
	 *
	 * @return string The raw path (i.e. not urldecoded)
	 */
	public function getPathInfo() {
		if (null === $this->pathInfo) {
			$this->pathInfo = $this->preparePathInfo();
		}

		return $this->pathInfo;
	}

	/**
	 * Gets the normalized query string for the Request.
	 *
	 * It builds a normalized query string, where keys/value pairs are alphabetized
	 * and have consistent escaping.
	 *
	 * @return string|null A normalized query string for the Request
	 */
	public function getQueryString() {
		$qs = Elgg_Http_Request::normalizeQueryString($this->server->get('QUERY_STRING'));

		return '' === $qs ? null : $qs;
	}

	/**
	 * Get URL segments from the path info
	 *
	 * @see Elgg_Http_Request::getPathInfo()
	 *
	 * @return array
	 */
	public function getUrlSegments() {
		$path = trim($this->query->get('__elgg_uri'), '/');
		if (!$path) {
			return array();
		}

		return explode('/', $path);
	}

	/**
	 * Get first URL segment from the path info
	 *
	 * @see Elgg_Http_Request::getUrlSegments()
	 *
	 * @return string
	 */
	public function getFirstUrlSegment() {
		$segments = $this->getUrlSegments();
		if ($segments) {
			return array_shift($segments);
		} else {
			return '';
		}
	}

	/**
	 * Get the IP address of the client
	 *
	 * @return string
	 */
	public function getClientIp() {
		$server = $this->server;

		$ip_address = $server->get('HTTP_X_FORWARDED_FOR');
		if (!empty($ip_address)) {
			return array_pop(explode(',', $ip_address));
		}

		$ip_address = $server->get('HTTP_X_REAL_IP');
		if (!empty($ip_address)) {
			return array_pop(explode(',', $ip_address));
		}

		return $server->get('REMOTE_ADDR');
	}

	/**
	 * Is this an ajax request
	 *
	 * @return bool
	 */
	public function isXmlHttpRequest() {
		return $this->headers->get('X-Requested-With') == 'XMLHttpRequest' ||
			$this->get('X-Requested-With') === 'XMLHttpRequest';
	}

	/**
	 * Strip slashes if magic quotes is on
	 *
	 * @param mixed $data Data to strip slashes from
	 * @return mixed
	 */
	protected function stripSlashesIfMagicQuotes($data) {
		if (get_magic_quotes_gpc()) {
			return _elgg_stripslashes_deep($data);
		} else {
			return $data;
		}
	}

	/**
	 * Get the HTTP headers from server
	 *
	 * @return array
	 */
	protected function prepareHeaders() {
		$headers = array();
		$contentHeaders = array('CONTENT_LENGTH' => true, 'CONTENT_MD5' => true, 'CONTENT_TYPE' => true);
		foreach ($this->server as $key => $value) {
			if (0 === strpos($key, 'HTTP_')) {
				$key = strtr(strtolower(substr($key, 5)), '_', '-');
				$key = implode('-', array_map('ucfirst', explode('-', $key)));
				$headers[$key] = $value;
			} elseif (isset($contentHeaders[$key])) {
				$key = strtr(strtolower($key), '_', '-');
				$key = implode('-', array_map('ucfirst', explode('-', $key)));
				$headers[$key] = $value;
			}
		}

		return $headers;
	}

	/**
	 * Set the request URI
	 *
	 * The Symfony Request object handles proxies and IIS rewrites. We are not
	 * yet.
	 *
	 * @return string
	 */
	protected function prepareRequestUri() {
		return $this->server->get('REQUEST_URI');
	}

	/**
	 * Prepares the base URL.
	 *
	 * @return string
	 */
	protected function prepareBaseUrl() {
		$filename = basename($this->server->get('SCRIPT_FILENAME'));

		if (basename($this->server->get('SCRIPT_NAME')) === $filename) {
			$baseUrl = $this->server->get('SCRIPT_NAME');
		} elseif (basename($this->server->get('PHP_SELF')) === $filename) {
			$baseUrl = $this->server->get('PHP_SELF');
		} elseif (basename($this->server->get('ORIG_SCRIPT_NAME')) === $filename) {
			$baseUrl = $this->server->get('ORIG_SCRIPT_NAME'); // 1and1 shared hosting compatibility
		} else {
			// Backtrack up the script_filename to find the portion matching
			// php_self
			$path = $this->server->get('PHP_SELF', '');
			$file = $this->server->get('SCRIPT_FILENAME', '');
			$segs = explode('/', trim($file, '/'));
			$segs = array_reverse($segs);
			$index = 0;
			$last = count($segs);
			$baseUrl = '';
			do {
				$seg = $segs[$index];
				$baseUrl = '/' . $seg . $baseUrl;
				++$index;
			} while (($last > $index) && (false !== ($pos = strpos($path, $baseUrl))) && (0 != $pos));
		}

		// Does the baseUrl have anything in common with the request_uri?
		$requestUri = $this->getRequestUri();

		if ($baseUrl && false !== $prefix = $this->getUrlencodedPrefix($requestUri, $baseUrl)) {
			// full $baseUrl matches
			return $prefix;
		}

		if ($baseUrl && false !== $prefix = $this->getUrlencodedPrefix($requestUri, dirname($baseUrl))) {
			// directory portion of $baseUrl matches
			return rtrim($prefix, '/');
		}

		$truncatedRequestUri = $requestUri;
		if (($pos = strpos($requestUri, '?')) !== false) {
			$truncatedRequestUri = substr($requestUri, 0, $pos);
		}

		$basename = basename($baseUrl);
		if (empty($basename) || !strpos(rawurldecode($truncatedRequestUri), $basename)) {
			// no match whatsoever; set it blank
			return '';
		}

		// If using mod_rewrite or ISAPI_Rewrite strip the script filename
		// out of baseUrl. $pos !== 0 makes sure it is not matching a value
		// from PATH_INFO or QUERY_STRING
		if ((strlen($requestUri) >= strlen($baseUrl)) && ((false !== ($pos = strpos($requestUri, $baseUrl))) && ($pos !== 0))) {
			$baseUrl = substr($requestUri, 0, $pos + strlen($baseUrl));
		}

		return rtrim($baseUrl, '/');
	}

	/**
	 * Prepares the path info.
	 *
	 * @return string path info
	 */
	protected function preparePathInfo() {
		$baseUrl = $this->getBaseUrl();

		if (null === ($requestUri = $this->getRequestUri())) {
			return '/';
		}

		$pathInfo = '/';

		// Remove the query string from REQUEST_URI
		if ($pos = strpos($requestUri, '?')) {
			$requestUri = substr($requestUri, 0, $pos);
		}

		if ((null !== $baseUrl) && (false === ($pathInfo = substr($requestUri, strlen($baseUrl))))) {
			// If substr() returns false then PATH_INFO is set to an empty string
			return '/';
		} elseif (null === $baseUrl) {
			return $requestUri;
		}

		return (string) $pathInfo;
	}

	/**
	 * Returns the prefix as encoded in the string when the string starts with
	 * the given prefix, false otherwise.
	 *
	 * @param string $string The urlencoded string
	 * @param string $prefix The prefix not encoded
	 *
	 * @return string|false The prefix as it is encoded in $string, or false
	 */
	private function getUrlencodedPrefix($string, $prefix) {
		if (0 !== strpos(rawurldecode($string), $prefix)) {
			return false;
		}

		$len = strlen($prefix);

		if (preg_match("#^(%[[:xdigit:]]{2}|.){{$len}}#", $string, $match)) {
			return $match[0];
		}

		return false;
	}

}
