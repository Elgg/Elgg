<?php
namespace Elgg\Http;

use Elgg\Context;
use Elgg\Filesystem\File;
use Elgg\Filesystem\Filesystem;
use Elgg\PluginHooksService;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Represents an HTTP request.
 * 
 * @package    Elgg.Core
 * @subpackage Http
 * @since      1.10.0
 * 
 * @access private
 */
class Request {

	/** @var Context */
	private $context;
	
	/** @var PluginHooksService */
	private $hooks;
	
	/** @var SymfonyRequest */
	private $symfony;
	
	/** @var Url */
	private $wwwroot;
	
	/** @var ParameterBag */
	private $input;
	
	/**
	 * Create a request
	 * 
	 * @param Context            $context For pushing the input context.
	 * @param PluginHooksService $hooks   For triggering input filters.
	 * @param SymfonyRequest     $symfony Provides much of the logic.
	 * @param Url                $wwwroot The site's base URL
	 */
	public function __construct(
			Context $context,
			PluginHooksService $hooks,
			SymfonyRequest $symfony,
			Url $wwwroot) {
		$this->context = $context;
		$this->hooks = $hooks;
		$this->symfony = $symfony;
		$this->wwwroot = $wwwroot;
		$this->input = new ParameterBag();
	}

	/**
	 * Creates a request from PHP's globals
	 *
	 * @return Request
	 */
	public static function createFromGlobals() {
		$context = _elgg_services()->context;
		$hooks = _elgg_services()->hooks;
		$request = SymfonyRequest::createFromGlobals();

		return new Request($context, $hooks, $request, Url::parse(elgg_get_site_url()));
	}
	
	/**
	 * Create a new request object based on the given URI.
	 * 
	 * @param string $uri The URI
	 * 
	 * @return Request
	 */
	public static function create($uri) {
		$context = _elgg_services()->context;
		$hooks = _elgg_services()->hooks;
		$request = SymfonyRequest::create($uri);
		
		return new Request($context, $hooks, $request, Url::parse(elgg_get_site_url()));
	}

	/**
	 * Get some input from variables passed submitted through GET or POST.
	 *
	 * If using any data obtained from getInput() in a web page, please be aware that
	 * it is a possible vector for a reflected XSS attack. If you are expecting an
	 * integer, cast it to an int. If it is a string, escape quotes.
	 *
	 * Note: this function does not handle nested arrays (ex: form input of param[m][n])
	 * because of the filtering done in htmlawed from the filter_tags call.
	 * @todo Is this ^ still true?
	 *
	 * @param string $variable      The variable name we want.
	 * @param mixed  $default       A default value for the variable if it is not found.
	 * @param bool   $filter_result If true, then the result is filtered for bad tags.
	 *
	 * @return mixed
	 */
	public function get($variable, $default = null, $filter_result = true) {
		$this->context->push('input');

		$result = $default;
		$result = $this->symfony->get($variable, $result);
		$result = $this->input->get($variable, $result);

		// @todo why trim?
		if (is_string($result)) {
			$result = trim($result);
		}
		
		if ($filter_result && isset($result)) {
			$result = $this->filterTags($result);
		}

		$this->context->pop();

		return $result;
	}

	/**
	 * Sets an input value that may later be retrieved by get_input
	 *
	 * Note: this function does not handle nested arrays (ex: form input of param[m][n])
	 *
	 * @param string          $variable The name of the variable
	 * @param string|string[] $value    The value of the variable
	 *
	 * @return void
	 */
	public function set($variable, $value) {
		if (is_array($value)) {
			$value = array_map(function($v) {
				return trim($v);
			}, $value);
		} else {
			$value = trim($value);
		}

		$this->input->set(trim($variable), $value);
	}
	
	/**
	 * Filter tags from a given string based on registered hooks.
	 *
	 * @param mixed $var Anything that does not include an object (strings, ints, arrays)
	 *					 This includes multi-dimensional arrays.
	 *
	 * @return mixed The filtered result - everything will be strings
	 */
	public function filterTags($var) {
		return $this->hooks->trigger('validate', 'input', null, $var);
	}
	
	/**
	 * Returns the current page's complete URL.
	 * 
	 * 
	 * It uses the configured site URL for the hostname rather than depending on
	 * what the server uses to populate $_SERVER.
	 *
	 * @return Url The current page URL.
	 */
	public function getCurrentPageUrl() {
		return $this->wwwroot->getOrigin()->setPath($this->symfony->getRequestUri());
	}

	/**
	 * Get URL segments from the path info
	 *
	 * @return array
	 */
	public function getUrlSegments() {
		$path = trim($this->symfony->get('__elgg_uri'), '/');
		if (!$path) {
			return array();
		}

		return explode('/', $path);
	}

	/**
	 * Get first URL segment from the path info
	 *
	 * @see Request::getUrlSegments()
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
	 * Is this an ajax request
	 *
	 * @return bool
	 */
	public function isXmlHttpRequest() {
		return $this->symfony->isXmlHttpRequest();
	}
	
	/**
	 * Return the full URL of the current page.
	 *
	 * @return string The URL
	 * @deprecated 1.9 Use getCurrentPageUrl instead.
	 */
	public function getFullUrl() {
		$url = $this->symfony->getSchemeAndHttpHost();
	
		// This is here to prevent XSS in poorly written browsers used by 80% of the population.
		// svn commit [5813]: https://github.com/Elgg/Elgg/commit/0c947e80f512cb0a482b1864fd0a6965c8a0cd4a
		// @todo encoding like this should occur when inserting into web page, not here
		$quotes = array('\'', '"');
		$encoded = array('%27', '%22');
		return $url . str_replace($quotes, $encoded, $this->symfony->getRequestUri());
	}
	
	/**
	 * Forward to $location.
	 *
	 * Sends a 'Location: $location' header and exists.  If headers have
	 * already been sent, throws an exception.
	 *
	 * @param string $location URL to forward to browser to. This can be a path
	 *                         relative to the network's URL.
	 * @param string $reason   Short explanation for why we're forwarding. Set to
	 *                         '404' to forward to error page. Default message is
	 *                         'system'.
	 *
	 * @return void
	 * @throws SecurityException
	 */
	public function forward($location, $reason = 'system') {
		if (headers_sent($file, $line)) {
			throw new \SecurityException("Redirect could not be issued due to headers already being sent. Halting execution for security. "
				. "Output started in file $file at line $line. Search http://docs.elgg.org/ for more information.");
		}
		
		if ($location === REFERER) {
			$location = $this->symfony->headers->get('Referer');
		}

		$location = $this->wwwroot->normalize($location);

		// return new forward location or false to stop the forward or empty string to exit
		$current_page = $this->getCurrentPageUrl();
		$params = array('current_url' => "$current_page", 'forward_url' => $location);
		return $this->hooks->trigger('forward', $reason, $params, $location);
	}
	
	/**
	 * Pull out GET parameters through filter
	 * 
	 * @return array
	 */
	public function getQueryParams() {
		$params = array();
		
		foreach ($this->symfony->query->keys() as $name) {
			$params[$name] = $this->get($name);
		}
		
		return $params;
	}
	
	/**
	 * Get a file uploaded under the given name.
	 * 
	 * @param string $key The input name.
	 * 
	 * @return File
	 */
	public function getFile($key) {
		$files = $this->symfony->files;
		if (!$files->has($input_name)) {
			return null;
		}
		
		$file = $files->get($key);
		
		if (elgg_extract('error', $file) !== 0) {
			return null;
		}
		
		$fs = Filesystem::createFromLocal('/');
		return $fs->getFile(elgg_extract('tmp_name', $file));
	}

	/**
	 * Return all GET and POST parameters without filtering.
	 * 
	 * @return array
	 */
	public function all() {
		$query = $this->symfony->query->all();
		$request = $this->symfony->request->all();
		
		return array_merge($query, $request);
	}
	
	/**
	 * Returns the client IP address.
	 * 
	 * @return string
	 */
	public function getClientIp() {
		return $this->symfony->getClientIp();
	}
}
