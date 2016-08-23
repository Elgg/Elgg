<?php
namespace Elgg\Application;

use Elgg\Application;
use Elgg\Config;


/**
 * Simplecache handler
 *
 * @access private
 *
 * @package Elgg.Core
 */
class CacheHandler {
	
	public static $extensions = [
		'bmp' => "image/bmp",
		'css' => "text/css",
		'gif' => "image/gif",
		'html' => "text/html",
		'ico' => "image/x-icon",
		'jpeg' => "image/jpeg",
		'jpg' => "image/jpeg",
		'js' => "application/javascript",
		'png' => "image/png",
		'svg' => "image/svg+xml",
		'swf' => "application/x-shockwave-flash",
		'tiff' => "image/tiff",
		'webp' => "image/webp",
		'xml' => "text/xml",
		'eot' => "application/vnd.ms-fontobject",
		'ttf' => "application/font-ttf",
		'woff' => "application/font-woff",
		'woff2' => "application/font-woff2",
		'otf' => "application/font-otf",
	];

	public static $utf8_content_types = [
		"text/css",
		"text/html",
		"application/javascript",
		"image/svg+xml",
		"text/xml",
	];

	/** @var Application */
	private $application;

	/** @var Config */
	private $config;

	/** @var array */
	private $server_vars;

	/**
	 * Constructor
	 *
	 * @param Application $app         Elgg Application
	 * @param Config      $config      Elgg configuration
	 * @param array       $server_vars Server vars
	 */
	public function __construct(Application $app, Config $config, $server_vars) {
		$this->application = $app;
		$this->config = $config;
		$this->server_vars = $server_vars;
	}

	/**
	 * Handle a request for a cached view
	 *
	 * @param array $path URL path
	 * @return void
	 */
	public function handleRequest($path) {
		$config = $this->config;
		
		$request = $this->parsePath($path);
		if (!$request) {
			$this->send403();
		}
		
		$ts = $request['ts'];
		$view = $request['view'];
		$viewtype = $request['viewtype'];

		$content_type = $this->getContentType($view);
		if (empty($content_type)) {
			$this->send403("Asset must have a valid file extension");
		}

		if (in_array($content_type, self::$utf8_content_types)) {
			header("Content-Type: $content_type;charset=utf-8");
		} else {
			header("Content-Type: $content_type");
		}

		// this may/may not have to connect to the DB
		$this->setupSimplecache();

		// we can't use $config->get yet. It fails before the core is booted
		if (!$config->getVolatile('simplecache_enabled')) {

			$this->application->bootCore();

			if (!$this->isCacheableView($view)) {
				$this->send403("Requested view is not an asset");
			} else {
				$content = $this->renderView($view, $viewtype);
				$etag = '"' . md5($content) . '"';
				$this->sendRevalidateHeaders($etag);
				$this->handle304($etag);

				echo $content;
			}
			exit;
		}

		$etag = "\"$ts\"";
		$this->handle304($etag);

		// trust the client but check for an existing cache file
		$filename = $config->getVolatile('cacheroot') . "views_simplecache/$ts/$viewtype/$view";
		if (file_exists($filename)) {
			$this->sendCacheHeaders($etag);
			readfile($filename);
			exit;
		}

		// the hard way
		$this->application->bootCore();

		elgg_set_viewtype($viewtype);
		if (!$this->isCacheableView($view)) {
			$this->send403("Requested view is not an asset");
		}

		$lastcache = (int)$config->get('lastcache');

		$filename = $config->getVolatile('cacheroot') . "views_simplecache/$lastcache/$viewtype/$view";

		if ($lastcache == $ts) {
			$this->sendCacheHeaders($etag);

			$content = $this->getProcessedView($view, $viewtype);

			$dir_name = dirname($filename);
			if (!is_dir($dir_name)) {
				mkdir($dir_name, 0700, true);
			}

			file_put_contents($filename, $content);
		} else {
			// if wrong timestamp, don't send HTTP cache
			$content = $this->renderView($view, $viewtype);
		}

		echo $content;
		exit;
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
			return array();
		}
		// only alphanumeric characters plus /, ., -, and _
		if (preg_match('#[^a-zA-Z0-9/\.\-_]#', $path)) {
			return array();
		}

		// testing showed regex to be marginally faster than array / string functions over 100000 reps
		// it won't make a difference in real life and regex is easier to read.
		// <ts>/<viewtype>/<name/of/view.and.dots>.<type>
		if (!preg_match('#^/cache/([0-9]+)/([^/]+)/(.+)$#', $path, $matches)) {
			return array();
		}

		return array(
			'ts' => $matches[1],
			'viewtype' => $matches[2],
			'view' => $matches[3],
		);
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
			return in_array($m[1],  _elgg_services()->translator->getAllLanguageCodes());
		}
		return _elgg_services()->views->isCacheableView($view);
	}

	/**
	 * Do a minimal engine load
	 *
	 * @return void
	 */
	protected function setupSimplecache() {
		// we can't use Elgg\Config::get yet. It fails before the core is booted
		$config = $this->config;
		$config->loadSettingsFile();

		if ($config->getVolatile('cacheroot') && $config->getVolatile('simplecache_enabled') !== null) {
			// we can work with these...
			return;
		}

		$db = $this->application->getDb();

		try {
			$rows = $db->getData("
				SELECT `name`, `value`
				FROM {$db->prefix}datalists
				WHERE `name` IN ('dataroot', 'simplecache_enabled')
			");
			if (!$rows) {
				$this->send403('Cache error: unable to get the data root');
			}
		} catch (\DatabaseException $e) {
			if (0 === strpos($e->getMessage(), "Elgg couldn't connect")) {
				$this->send403('Cache error: unable to connect to database server');
			} else {
				$this->send403('Cache error: unable to connect to Elgg database');
			}
			exit; // unnecessary, but helps PhpStorm understand
		}

		foreach ($rows as $row) {
			$config->set($row->name, $row->value);
		}

		if (!$config->getVolatile('cacheroot')) {
			$dataroot = $config->getVolatile('dataroot');
			if (!$dataroot) {
				$this->send403('Cache error: unable to get the cache root');
			}
			$config->set('cacheroot', $dataroot);
		}
	}

	/**
	 * Send cache headers
	 *
	 * @param string $etag ETag value
	 * @return void
	 */
	protected function sendCacheHeaders($etag) {
		header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+6 months")), true);
		header("Pragma: public", true);
		header("Cache-Control: public", true);
		header("ETag: $etag");
	}

	/**
	 * Send revalidate cache headers
	 *
	 * @param string $etag ETag value
	 * @return void
	 */
	protected function sendRevalidateHeaders($etag) {
		header_remove('Expires');
		header("Pragma: public", true);
		header("Cache-Control: public, max-age=0, must-revalidate", true);
		header("ETag: $etag");
	}

	/**
	 * Send a 304 and exit() if the ETag matches the request
	 *
	 * @param string $etag ETag value
	 * @return void
	 */
	protected function handle304($etag) {
		if (!isset($this->server_vars['HTTP_IF_NONE_MATCH'])) {
			return;
		}

		// strip -gzip for #9427
		$if_none_match = str_replace('-gzip', '', trim($this->server_vars['HTTP_IF_NONE_MATCH']));
		if ($if_none_match === $etag) {
			header("HTTP/1.1 304 Not Modified");
			exit;
		}
	}

	/**
	 * Get the content type
	 *
	 * @param string $view The view name
	 *
	 * @return string|null
	 */
	protected function getContentType($view) {
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
	private function getViewFileType($view) {
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
	 * @return string
	 * @see CacheHandler::renderView()
	 */
	protected function getProcessedView($view, $viewtype) {
		$content = $this->renderView($view, $viewtype);

		$hook_type = $this->getViewFileType($view);
		$hook_params = array(
			'view' => $view,
			'viewtype' => $viewtype,
			'view_content' => $content,
		);
		return \_elgg_services()->hooks->trigger('simplecache:generate', $hook_type, $hook_params, $content);
	}

	/**
	 * Render a view for caching. Language views are handled specially.
	 *
	 * @param string $view     The view name
	 * @param string $viewtype The viewtype
	 * @return string
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
			$this->send403();
		}

		// disable error reporting so we don't cache problems
		$this->config->set('debug', null);

		// @todo elgg_view() checks if the page set is done (isset($GLOBALS['_ELGG']->pagesetupdone)) and
		// triggers an event if it's not. Calling elgg_view() here breaks submenus
		// (at least) because the page setup hook is called before any
		// contexts can be correctly set (since this is called before page_handler()).
		// To avoid this, lie about $CONFIG->pagehandlerdone to force
		// the trigger correctly when the first view is actually being output.
		$GLOBALS['_ELGG']->pagesetupdone = true;

		return elgg_view($view, $vars);
	}

	/**
	 * Send an error message to requestor
	 *
	 * @param string $msg Optional message text
	 * @return void
	 */
	protected function send403($msg = 'Cache error: bad request') {
		header('HTTP/1.1 403 Forbidden');
		echo $msg;
		exit;
	}
}

