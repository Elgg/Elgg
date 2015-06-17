<?php
namespace Elgg\Application;

use Elgg\Application;

/**
 * Simplecache handler
 *
 * @access private
 *
 * @package Elgg.Core
 */
class CacheHandler {

	/**
	 * @var Application
	 */
	private $application;

	/**
	 * @var array
	 */
	private $server_vars;

	/**
	 * Constructor
	 *
	 * @param Application $app         Elgg Application
	 * @param array       $server_vars Server vars
	 */
	public function __construct(Application $app, $server_vars) {
		$this->application = $app;
		$this->server_vars = $server_vars;
	}

	/**
	 * Handle a request for a cached view
	 *
	 * @param array $path URL path
	 * @return void
	 */
	public function handleRequest($path) {
		$config = $this->application->config;

		$request = $this->parsePath($path);
		if (!$request) {
			$this->send403();
		}
		
		$ts = $request['ts'];
		$view = $request['view'];
		$viewtype = $request['viewtype'];

		$contentType = $this->getContentType($view);
		if (!empty($contentType)) {
			header("Content-Type: $contentType", true);
		}

		// this may/may not have to connect to the DB
		$this->setupSimplecache();

		// we can't use $config->get yet. It fails before the core is booted
		if (!$config->getVolatile('simplecache_enabled')) {

			$this->application->bootCore();


			if (!_elgg_services()->views->get($view)->isCacheable()) {
				$this->send403();
			} else {
				echo $this->renderView($view, $viewtype);
			}
			exit;
		}

		$etag = "\"$ts\"";
		// If is the same ETag, content didn't change.
		if (isset($this->server_vars['HTTP_IF_NONE_MATCH'])
				&& trim($this->server_vars['HTTP_IF_NONE_MATCH']) === $etag) {
			header("HTTP/1.1 304 Not Modified");
			exit;
		}

		$filename = $config->getVolatile('dataroot') . 'views_simplecache/' . md5("$viewtype|$view");
		if (file_exists($filename)) {
			$this->sendCacheHeaders($etag);
			readfile($filename);
			exit;
		}

		$this->application->bootCore();

		elgg_set_viewtype($viewtype);
		if (!_elgg_services()->views->get($view)->isCacheable()) {
			$this->send403();
		}

		$cache_timestamp = (int)$config->get('lastcache');

		if ($cache_timestamp == $ts) {
			$this->sendCacheHeaders($etag);

			$content = $this->getProcessedView($view, $viewtype);

			$dir_name = $config->getDataPath() . 'views_simplecache/';
			if (!is_dir($dir_name)) {
				mkdir($dir_name, 0700);
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
	 * Do a minimal engine load
	 *
	 * @return void
	 */
	protected function setupSimplecache() {
		// we can't use Elgg\Config::get yet. It fails before the core is booted
		$config = $this->application->config;
		$config->loadSettingsFile();

		if ($config->getVolatile('dataroot') && $config->getVolatile('simplecache_enabled') !== null) {
			// we can work with these...
			return;
		}

		$db = $this->application->getDb();

		try {
			$rows = $db->getData("
				SELECT `name`, `value`
				FROM {$db->getTablePrefix()}datalists
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

		if (!$config->getVolatile('dataroot')) {
			$this->send403('Cache error: unable to get the data root');
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
	 * Get the content type
	 *
	 * @param string $view The view name
	 * 
	 * @return string?
	 */
	protected function getContentType($view) {
		
		$extension = $this->getViewFileType($view);
		
		switch ($extension) {
			case 'gif':
			case 'png':
			case 'webp':
			case 'bmp':
			case 'tiff':
			case 'jpeg':
				return "image/$extension";
			
			case 'jpg':
				return "image/jpeg";

			case 'ico':
				return 'image/x-icon';
			
			case 'svg':
				return 'image/svg+xml';
			
			case 'js':
				return 'application/javascript';
			
			case 'css':
			case 'html':
			case 'xml':
				return "text/$extension";
			
			default:
				break;
		}
	}
	
	/**
	 * Returns the type of output expected from the view.
	 * 
	 *  - view/name.extension returns "extension"
	 *  - css/view views return "css"
	 *  - js/view views return "js"
	 *  - Otherwise, returns "unknown"
	 *
	 * @param string $view The view name
	 * @return string
	 */
	private function getViewFileType($view) {
		$extension = (new \SplFileInfo($view))->getExtension();
		if ($extension) {
			return $extension;
		}
		
		if (preg_match('~(?:^|/)(css|js)(?:$|/)~', $view, $m)) {
			return $m[1];
		} else {
			return 'unknown';
		}
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
		return _elgg_services()->hooks->trigger('simplecache:generate', $hook_type, $hook_params, $content);
	}

	/**
	 * Render a view for caching
	 *
	 * @param string $view     The view name
	 * @param string $viewtype The viewtype
	 * @return string
	 */
	protected function renderView($view, $viewtype) {
		elgg_set_viewtype($viewtype);

		if (!elgg_view_exists($view)) {
			$this->send403();
		}

		// disable error reporting so we don't cache problems
		$this->application->config->set('debug', null);

		// @todo elgg_view() checks if the page set is done (isset($CONFIG->pagesetupdone)) and
		// triggers an event if it's not. Calling elgg_view() here breaks submenus
		// (at least) because the page setup hook is called before any
		// contexts can be correctly set (since this is called before page_handler()).
		// To avoid this, lie about $CONFIG->pagehandlerdone to force
		// the trigger correctly when the first view is actually being output.
		$this->application->config->set('pagesetupdone', true);

		return elgg_view($view);
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

