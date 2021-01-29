<?php

namespace Elgg\Http;

use Elgg\Context;
use Elgg\Exceptions\Http\BadRequestException;
use Elgg\Router\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Elgg\Config;

/**
 * Elgg HTTP request.
 *
 * @internal
 */
class Request extends SymfonyRequest {

	const REWRITE_TEST_TOKEN = '__testing_rewrite';
	const REWRITE_TEST_OUTPUT = 'success';

	/**
	 * @var Context
	 */
	protected $context_stack;

	/**
	 * @var Route
	 */
	protected $route;
	
	/**
	 * @ var array
	 */
	protected $request_overrides;

	/**
	 * {@inheritdoc}
	 */
	public function __construct(
		array $query = [],
		array $request = [],
		array $attributes = [],
		array $cookies = [],
		array $files = [],
		array $server = [],
		$content = null
	) {
		parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);

		$this->initializeContext();
		
		$this->request_overrides = [];
	}
	
	/**
	 * Configure trusted proxy servers to allow access to more client information
	 *
	 * @return void
	 */
	public function initializeTrustedProxyConfiguration(Config $config) {
		$trusted_proxies = $config->http_request_trusted_proxy_ips;
		if (empty($trusted_proxies)) {
			return;
		}
		
		$allowed_headers = $config->http_request_trusted_proxy_headers;
		if (empty($allowed_headers)) {
			$allowed_headers = self::HEADER_X_FORWARDED_ALL;
		}
		
		$this->setTrustedProxies($trusted_proxies, $allowed_headers);
	}

	/**
	 * Initialize context stack
	 * @return static
	 */
	public function initializeContext() {
		$context = new Context($this);
		$this->context_stack = $context;

		return $this;
	}

	/**
	 * Returns context stack
	 * @return Context
	 */
	public function getContextStack() {
		return $this->context_stack;
	}

	/**
	 * Sets the route matched for this request by the router
	 *
	 * @param Route $route Route
	 *
	 * @return static
	 */
	public function setRoute(Route $route) {
		$this->route = $route;
		foreach ($route->getMatchedParameters() as $key => $value) {
			$this->setParam($key, $value);
		}

		return $this;
	}

	/**
	 * Returns the route matched for this request by the router
	 * @return Route|null
	 */
	public function getRoute() {
		return $this->route;
	}

	/**
	 * Sets an input value that may later be retrieved by get_input
	 *
	 * Note: this function does not handle nested arrays (ex: form input of param[m][n])
	 *
	 * @param string          $key              The name of the variable
	 * @param string|string[] $value            The value of the variable
	 * @param bool            $override_request The variable should override request values (default: false)
	 *
	 * @return static
	 */
	public function setParam($key, $value, $override_request = false) {
		if ((bool) $override_request) {
			$this->request_overrides[$key] = $value;
		} else {
			$this->request->set($key, $value);
		}

		return $this;
	}

	/**
	 * Get some input from variables passed submitted through GET or POST.
	 *
	 * If using any data obtained from get_input() in a web page, please be aware that
	 * it is a possible vector for a reflected XSS attack. If you are expecting an
	 * integer, cast it to an int. If it is a string, escape quotes.
	 *
	 * @param string $key           The variable name we want.
	 * @param mixed  $default       A default value for the variable if it is not found.
	 * @param bool   $filter_result If true, then the result is filtered for bad tags.
	 *
	 * @return mixed
	 */
	public function getParam($key, $default = null, $filter_result = true) {
		$result = $default;

		$values = $this->getParams($filter_result);

		$value = elgg_extract($key, $values, $default);
		if ($value !== null) {
			$result = $value;
		}

		return $result;
	}

	/**
	 * Returns all values parsed from the request
	 *
	 * @param bool $filter_result Sanitize input values
	 *
	 * @return array
	 */
	public function getParams($filter_result = true) {
		$request_overrides = $this->request_overrides;
		$query = $this->query->all();
		$attributes = $this->attributes->all();
		$post = $this->request->all();

		$result = array_merge($post, $attributes, $query, $request_overrides);

		if ($filter_result) {
			$this->getContextStack()->push('input');
			
			$result = filter_tags($result);
			
			$this->getContextStack()->pop();
		}

		return $result;
	}

	/**
	 * Returns current page URL
	 *
	 * @return string
	 */
	public function getCurrentURL() {
		$url = parse_url(elgg_get_site_url());

		$page = $url['scheme'] . "://" . $url['host'];

		if (isset($url['port']) && $url['port']) {
			$page .= ":" . $url['port'];
		}

		$page = trim($page, "/");

		$page .= $this->getRequestUri();

		return $page;
	}

	/**
	 * Get the Elgg URL segments
	 *
	 * @param bool $raw If true, the segments will not be HTML escaped
	 *
	 * @return string[]
	 */
	public function getUrlSegments($raw = false) {
		$path = trim($this->getElggPath(), '/');
		if (!$raw) {
			$path = htmlspecialchars($path, ENT_QUOTES, 'UTF-8');
		}
		if (!$path) {
			return [];
		}

		return explode('/', $path);
	}

	/**
	 * Get a cloned request with new Elgg URL segments
	 *
	 * @param string[] $segments URL segments
	 *
	 * @return Request
	 */
	public function setUrlSegments(array $segments) {
		$base_path = trim($this->getBasePath(), '/');
		$server = $this->server->all();
		$server['REQUEST_URI'] = "$base_path/" . implode('/', $segments);

		return $this->duplicate(null, null, null, null, null, $server);
	}

	/**
	 * Get first Elgg URL segment
	 *
	 * @see \Elgg\Http\Request::getUrlSegments()
	 *
	 * @return string
	 */
	public function getFirstUrlSegment() {
		$segments = $this->getUrlSegments();
		if (!empty($segments)) {
			return array_shift($segments);
		}
		
		return '';
	}

	/**
	 * Get the Request URI minus querystring
	 *
	 * @return string
	 */
	public function getElggPath() {
		if (php_sapi_name() === 'cli-server') {
			$path = $this->getRequestUri();
		} else {
			$path = $this->getPathInfo();
		}

		return preg_replace('~(\?.*)$~', '', $path);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getClientIp() {
		$ip = parent::getClientIp();

		if ($ip == $this->server->get('REMOTE_ADDR')) {
			// try one more
			$ip_addresses = $this->server->get('HTTP_X_REAL_IP');
			if ($ip_addresses) {
				$ip_addresses = explode(',', $ip_addresses);

				return array_pop($ip_addresses);
			}
		}

		return $ip;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isXmlHttpRequest() {
		return (strtolower($this->headers->get('X-Requested-With')) === 'xmlhttprequest'
			|| $this->query->get('X-Requested-With') === 'XMLHttpRequest'
			|| $this->request->get('X-Requested-With') === 'XMLHttpRequest');
	}

	/**
	 * Sniff the Elgg site URL with trailing slash
	 *
	 * @return string
	 */
	public function sniffElggUrl() {
		$base_url = $this->getBaseUrl();

		// baseURL may end with the PHP script
		if ('.php' === substr($base_url, -4)) {
			$base_url = dirname($base_url);
		}

		$base_url = str_replace('\\', '/', $base_url);

		return rtrim($this->getSchemeAndHttpHost() . $base_url, '/') . '/';
	}

	/**
	 * Is the request for checking URL rewriting?
	 *
	 * @return bool
	 */
	public function isRewriteCheck() {
		if ($this->getPathInfo() !== ('/' . self::REWRITE_TEST_TOKEN)) {
			return false;
		}

		if (!$this->get(self::REWRITE_TEST_TOKEN)) {
			return false;
		}

		return true;
	}

	/**
	 * Is PHP running the CLI server front controller
	 *
	 * @return bool
	 */
	public function isCliServer() {
		return php_sapi_name() === 'cli-server';
	}

	/**
	 * Is the request pointing to a file that the CLI server can handle?
	 *
	 * @param string $root Root directory
	 *
	 * @return bool
	 */
	public function isCliServable($root) {
		$file = rtrim($root, '\\/') . $this->getElggPath();
		if (!is_file($file)) {
			return false;
		}

		// http://php.net/manual/en/features.commandline.webserver.php
		$extensions = ".3gp, .apk, .avi, .bmp, .css, .csv, .doc, .docx, .flac, .gif, .gz, .gzip, .htm, .html, .ics, .jpe, .jpeg, .jpg, .js, .kml, .kmz, .m4a, .mov, .mp3, .mp4, .mpeg, .mpg, .odp, .ods, .odt, .oga, .ogg, .ogv, .pdf, .pdf, .png, .pps, .pptx, .qt, .svg, .swf, .tar, .text, .tif, .txt, .wav, .webm, .wmv, .xls, .xlsx, .xml, .xsl, .xsd, and .zip";

		// The CLI server routes ALL requests here (even existing files), so we have to check for these.
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		if (!$ext) {
			return false;
		}

		$ext = preg_quote($ext, '~');

		return (bool) preg_match("~\\.{$ext}[,$]~", $extensions);
	}

	/**
	 * Returns an array of uploaded file objects regardless of upload status/errors
	 *
	 * @param string $input_name Form input name
	 *
	 * @return UploadedFile[]
	 */
	public function getFiles($input_name) {
		$files = $this->files->get($input_name);
		if (empty($files)) {
			return [];
		}

		if (!is_array($files)) {
			$files = [$files];
		}

		return $files;
	}

	/**
	 * Returns the first file found based on the input name
	 *
	 * @param string $input_name         Form input name
	 * @param bool   $check_for_validity If there is an uploaded file, is it required to be valid
	 *
	 * @return UploadedFile|false
	 */
	public function getFile($input_name, $check_for_validity = true) {
		$files = $this->getFiles($input_name);
		if (empty($files)) {
			return false;
		}

		$file = $files[0];
		if (empty($file)) {
			return false;
		}

		if ($check_for_validity && !$file->isValid()) {
			return false;
		}

		return $file;
	}

	/**
	 * Validate the request
	 *
	 * @return void
	 * @throws BadRequestException
	 */
	public function validate() {

		$reported_bytes = $this->server->get('CONTENT_LENGTH');

		// Requests with multipart content type
		$post_data_count = count($this->request->all());

		// Requests with other content types
		$content = $this->getContent();
		$post_body_length = is_string($content) ? elgg_strlen($content) : 0;

		$file_count = count($this->files->all());
		
		$is_valid = function() use ($reported_bytes, $post_data_count, $post_body_length, $file_count) {
			if (empty($reported_bytes)) {
				// Content length is set for POST requests only
				return true;
			}

			if (empty($post_data_count) && empty($post_body_length) && empty($file_count)) {
				// The size of $_POST or uploaded files has exceed the size limit
				// and the request body/query has been truncated
				// thus the request reported bytes is set, but no postdata is found
				return false;
			}

			return true;
		};

		if (!$is_valid()) {
			$error_msg = elgg_trigger_plugin_hook('action_gatekeeper:upload_exceeded_msg', 'all', [
				'post_size' => $reported_bytes,
				'visible_errors' => true,
			], elgg_echo('actiongatekeeper:uploadexceeded'));

			throw new BadRequestException($error_msg);
		}
	}
}
