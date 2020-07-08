<?php

namespace Elgg\Application;

use Elgg\Application;
use Elgg\Config;
use Elgg\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Simplecache handler
 *
 * @internal
 */
class CacheHandler {
	
	public static $extensions = [
		'bmp' => "image/bmp",
		'css' => "text/css",
		'eot' => "application/vnd.ms-fontobject",
		'gif' => "image/gif",
		'html' => "text/html",
		'ico' => "image/x-icon",
		'jpeg' => "image/jpeg",
		'jpg' => "image/jpeg",
		'js' => "application/javascript",
		'json' => "application/json",
		'map' => "application/json",
		'otf' => "application/font-otf",
		'png' => "image/png",
		'svg' => "image/svg+xml",
		'swf' => "application/x-shockwave-flash",
		'tiff' => "image/tiff",
		'ttf' => "application/font-ttf",
		'webp' => "image/webp",
		'woff' => "application/font-woff",
		'woff2' => "application/font-woff2",
		'xml' => "text/xml",
	];

	public static $utf8_content_types = [
		"text/css",
		"text/html",
		"application/javascript",
		"application/json",
		"image/svg+xml",
		"text/xml",
	];

	/** @var Config */
	private $config;

	/** @var Request */
	private $request;
	
	/** @var bool */
	protected $simplecache_enabled;

	/**
	 * Constructor
	 *
	 * @param Config  $config              Elgg configuration
	 * @param Request $request             HTTP request
	 * @param bool    $simplecache_enabled Is the simplecache enabled?
	 */
	public function __construct(Config $config, Request $request, $simplecache_enabled) {
		$this->config = $config;
		$this->request = $request;
		$this->simplecache_enabled = $simplecache_enabled;
	}

	/**
	 * Handle a request for a cached view
	 *
	 * @param Request     $request Elgg request
	 * @param Application $app     Elgg application
	 * @return Response (unprepared)
	 */
	public function handleRequest(Request $request, Application $app) {
		$config = $this->config;

		$parsed = $this->parsePath($request->getElggPath());
		if (!$parsed) {
			return $this->send403();
		}
		
		$ts = $parsed['ts'];
		$view = $parsed['view'];
		$viewtype = $parsed['viewtype'];

		$content_type = $this->getContentType($view);
		if (empty($content_type)) {
			return $this->send403("Asset must have a valid file extension");
		}

		$response = Response::create();
		if (in_array($content_type, self::$utf8_content_types)) {
			$response->headers->set('Content-Type', "$content_type;charset=utf-8", true);
		} else {
			$response->headers->set('Content-Type', $content_type, true);
		}
		
		$response->headers->set('X-Content-Type-Options', 'nosniff', true);

		if (!$this->simplecache_enabled) {
			$app->bootCore();
			if (!headers_sent()) {
				header_remove('Cache-Control');
				header_remove('Pragma');
				header_remove('Expires');
			}
			
			if (!$this->isCacheableView($view)) {
				return $this->send403("Requested view ({$view}) is not an asset");
			}

			$content = $this->getProcessedView($view, $viewtype);
			if ($content === false) {
				return $this->send403();
			}

			$etag = '"' . md5($content) . '"';
			$this->setRevalidateHeaders($etag, $response);
			if ($this->is304($etag)) {
				return Response::create()->setNotModified();
			}

			return $response->setContent($content);
		}

		$etag = "\"$ts\"";
		if ($this->is304($etag)) {
			return Response::create()->setNotModified();
		}

		// trust the client but check for an existing cache file
		$filename = $config->assetroot . "$ts/$viewtype/$view";
		if (file_exists($filename)) {
			$this->sendCacheHeaders($etag, $response);
			return BinaryFileResponse::create($filename, 200, $response->headers->all());
		}

		// the hard way
		$app->bootCore();
		header_remove('Cache-Control');
		header_remove('Pragma');
		header_remove('Expires');

		elgg_set_viewtype($viewtype);
		if (!$this->isCacheableView($view)) {
			return $this->send403("Requested view is not an asset");
		}

		$lastcache = (int) $config->lastcache;

		$filename = $config->assetroot . "$lastcache/$viewtype/$view";

		if ($lastcache == $ts) {
			$this->sendCacheHeaders($etag, $response);

			$content = $this->getProcessedView($view, $viewtype);

			$dir_name = dirname($filename);
			if (!is_dir($dir_name)) {
				// PHP and the server accessing the cache symlink may be a different user. And here
				// it's safe to make everything readable anyway.
				mkdir($dir_name, 0775, true);
			}

			file_put_contents($filename, $content);
			chmod($filename, 0664);
		} else {
			// if wrong timestamp, don't send HTTP cache
			$content = $this->getProcessedView($view, $viewtype);
		}

		return $response->setContent($content);
	}

	/**
	 * Parse a request
	 *
	 * @param string $path Request URL path
	 * @return array Cache parameters (empty array if failure)
	 */
	public function parsePath($path) {
		// no '..'
		if (false !== strpos($path, '..')) {
			return [];
		}
		// only alphanumeric characters plus /, ., -, and _
		if (preg_match('#[^a-zA-Z0-9/\.\-_]#', $path)) {
			return [];
		}

		// testing showed regex to be marginally faster than array / string functions over 100000 reps
		// it won't make a difference in real life and regex is easier to read.
		// <ts>/<viewtype>/<name/of/view.and.dots>.<type>
		if (!preg_match('#^/cache/([0-9]+)/([^/]+)/(.+)$#', $path, $matches)) {
			return [];
		}

		return [
			'ts' => $matches[1],
			'viewtype' => $matches[2],
			'view' => $matches[3],
		];
	}

	/**
	 * Is the view cacheable. Language views are handled specially.
	 *
	 * @param string $view View name
	 *
	 * @return bool
	 */
	protected function isCacheableView($view) {
		if (preg_match('~^languages/(.*)\.js$~', $view, $m)) {
			return in_array($m[1],  _elgg_services()->localeService->getLanguageCodes());
		}
		return _elgg_services()->views->isCacheableView($view);
	}

	/**
	 * Sets cache headers
	 *
	 * @param string   $etag     ETag value
	 * @param Response $response the response to set the headers on
	 *
	 * @return void
	 */
	protected function sendCacheHeaders($etag, Response $response) {
		$response->setSharedMaxAge(86400 * 30 * 6);
		$response->setMaxAge(86400 * 30 * 6);
		$response->headers->set('ETag', $etag);
	}

	/**
	 * Set revalidate cache headers
	 *
	 * @param string   $etag     ETag value
	 * @param Response $response the response to set the headers on
	 *
	 * @return void
	 */
	protected function setRevalidateHeaders($etag, Response $response) {
		$response->headers->set('Cache-Control', "public, max-age=0, must-revalidate", true);
		$response->headers->set('ETag', $etag);
	}

	/**
	 * Send a 304 and exit() if the ETag matches the request
	 *
	 * @param string $etag ETag value
	 * @return bool
	 */
	protected function is304($etag) {
		$if_none_match = $this->request->headers->get('If-None-Match');
		if ($if_none_match === null) {
			return false;
		}

		// strip -gzip and leading /W
		$if_none_match = trim($if_none_match);
		if (0 === strpos($if_none_match, 'W/')) {
			$if_none_match = substr($if_none_match, 2);
		}
		$if_none_match = str_replace('-gzip', '', $if_none_match);

		return ($if_none_match === $etag);
	}

	/**
	 * Get the content type
	 *
	 * @param string $view The view name
	 *
	 * @return string|null
	 */
	public function getContentType($view) {
		$extension = $this->getViewFileType($view);
		
		if (isset(self::$extensions[$extension])) {
			return self::$extensions[$extension];
		} else {
			return null;
		}
	}
	
	/**
	 * Returns the type of output expected from the view.
	 *
	 *  - view/name.extension returns "extension" if "extension" is valid
	 *  - css/view return "css"
	 *  - js/view return "js"
	 *  - Otherwise, returns "unknown"
	 *
	 * @param string $view The view name
	 * @return string
	 */
	public function getViewFileType($view) {
		$extension = (new \SplFileInfo($view))->getExtension();
		$hasValidExtension = isset(self::$extensions[$extension]);

		if ($hasValidExtension) {
			return $extension;
		}
		
		if (preg_match('~(?:^|/)(css|js)(?:$|/)~', $view, $m)) {
			return $m[1];
		}
		
		return 'unknown';
	}

	/**
	 * Get the contents of a view for caching
	 *
	 * @param string $view     The view name
	 * @param string $viewtype The viewtype
	 * @return string|false
	 * @see CacheHandler::renderView()
	 */
	protected function getProcessedView($view, $viewtype) {
		$content = $this->renderView($view, $viewtype);
		if ($content === false) {
			return false;
		}

		if ($this->simplecache_enabled) {
			$hook_name = 'simplecache:generate';
		} else {
			$hook_name = 'cache:generate';
		}
		$hook_type = $this->getViewFileType($view);
		$hook_params = [
			'view' => $view,
			'viewtype' => $viewtype,
			'view_content' => $content,
		];
		return \_elgg_services()->hooks->trigger($hook_name, $hook_type, $hook_params, $content);
	}

	/**
	 * Render a view for caching. Language views are handled specially.
	 *
	 * @param string $view     The view name
	 * @param string $viewtype The viewtype
	 * @return string|false
	 */
	protected function renderView($view, $viewtype) {
		elgg_set_viewtype($viewtype);

		if ($viewtype === 'default' && preg_match("#^languages/(.*?)\\.js$#", $view, $matches)) {
			$view = "languages.js";
			$vars = ['language' => $matches[1]];
		} else {
			$vars = [];
		}

		if (!elgg_view_exists($view)) {
			return false;
		}

		// disable error reporting so we don't cache problems
		$this->config->debug = null;

		return elgg_view($view, $vars);
	}

	/**
	 * Send an error message to requestor
	 *
	 * @param string $msg Optional message text
	 * @return Response
	 */
	protected function send403($msg = 'Cache error: bad request') {
		return Response::create($msg, 403);
	}
}
