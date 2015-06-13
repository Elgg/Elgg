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
	 * @var string
	 */
	private $dataroot;

	/**
	 * @var string
	 */
	private $simplecache_enabled;

	/**
	 * Constructor
	 *
	 * @param Application $app Elgg Application
	 */
	public function __construct(Application $app) {
		$this->application = $app;
	}

	/**
	 * Handle a request for a cached view
	 *
	 * @param array $path        URL path
	 * @param array $server_vars Server vars
	 * @return void
	 */
	public function handleRequest($path, $server_vars) {
		$config = $this->application->config;

		$request = $this->parsePath($path);
		if (!$request) {
			$this->send403();
		}
		$ts = $request['ts'];
		$view = $request['view'];
		$viewtype = $request['viewtype'];

		$this->sendContentType($view);

		// this may/may not have to connect to the DB
		$this->setupSimplecache();

		// we can't use $config->get yet. It fails before the core is booted
		if (!$this->simplecache_enabled) {

			$this->application->bootCore();

			if (!_elgg_is_view_cacheable($view)) {
				$this->send403();
			} else {
				echo $this->renderView($view, $viewtype);
			}
			exit;
		}

		$etag = "\"$ts\"";
		// If is the same ETag, content didn't change.
		if (isset($server_vars['HTTP_IF_NONE_MATCH']) && trim($server_vars['HTTP_IF_NONE_MATCH']) === $etag) {
			header("HTTP/1.1 304 Not Modified");
			exit;
		}

		$filename = "{$this->dataroot}views_simplecache/" . md5("$viewtype|$view");
		if (file_exists($filename)) {
			$this->sendCacheHeaders($etag);
			readfile($filename);
			exit;
		}

		$this->application->bootCore();

		elgg_set_viewtype($viewtype);
		if (!_elgg_is_view_cacheable($view)) {
			$this->send403();
		}

		$cache_timestamp = (int)$config->get('lastcache');

		if ($cache_timestamp == $ts) {
			$this->sendCacheHeaders($etag);

			$content = $this->getProcessedView($view, $viewtype);

			$dir_name = "{$this->dataroot}views_simplecache/";
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
	/**
	 * Do a minimal engine load
	 *
	 * @return void
	 */
	protected function setupSimplecache() {
		// we can't use Elgg\Config::get yet. It fails before the core is booted
		$config = $this->application->config;
		$config->loadSettingsFile();

		$path = $config->getVolatile('dataroot');
		$is_enabled = $config->getVolatile('simplecache_enabled');

		if ($path && $is_enabled !== null) {
			$this->dataroot = $path;
			$this->simplecache_enabled = $is_enabled;
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
			if ($row->name === 'dataroot') {
				$row->value = rtrim($row->value, '/\\') . DIRECTORY_SEPARATOR;
			}
			$config->set($row->name, $row->value);
		}

		$this->dataroot = $config->getVolatile('dataroot');
		$this->simplecache_enabled = $config->getVolatile('simplecache_enabled');
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
	 * Send content type
	 *
	 * @param string $view The view name
	 * @return void
	 */
	protected function sendContentType($view) {
		$segments = explode('/', $view, 2);
		switch ($segments[0]) {
			case 'css':
				header("Content-Type: text/css", true);
				break;
			case 'js':
				header('Content-Type: text/javascript', true);
				break;
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

		$hook_type = _elgg_get_view_filetype($view);
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

