<?php

/**
 * Simplecache handler
 *
 * @access private
 *
 * @package Elgg.Core
 */
class Elgg_CacheHandler {

	protected $config;

	/**
	 * Constructor
	 *
	 * @param stdClass $config Elgg config object
	 */
	public function __construct($config) {
		$this->config = $config;
	}

	/**
	 * Handle a request for a cached view
	 *
	 * @param array $get_vars    $_GET variables
	 * @param array $server_vars $_SERVER variables
	 * @return void
	 */
	public function handleRequest($get_vars, $server_vars) {
		if (empty($get_vars['request'])) {
			$this->send403();
		}
		$request = $this->parseRequestVar($get_vars['request']);
		if (!$request) {
			$this->send403();
		}
		$ts = $request['ts'];
		$view = $request['view'];
		$viewtype = $request['viewtype'];

		$this->sendContentType($view);

		// this may/may not have to connect to the DB
		$this->setupSimplecache();

		if (!$this->config->simplecache_enabled) {
			$this->loadEngine();
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

		$filename = $this->config->dataroot . 'views_simplecache/' . md5("$viewtype|$view");
		if (file_exists($filename)) {
			$this->sendCacheHeaders($etag);
			readfile($filename);
			exit;
		}

		$this->loadEngine();

		elgg_set_viewtype($viewtype);
		if (!_elgg_is_view_cacheable($view)) {
			$this->send403();
		}

		$cache_timestamp = (int)elgg_get_config('lastcache');

		if ($cache_timestamp == $ts) {
			$this->sendCacheHeaders($etag);

			$content = $this->getProcessedView($view, $viewtype);

			$dir_name = $this->config->dataroot . 'views_simplecache/';
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
	 * @param string $request_var Request URL
	 * @return array Cache parameters (empty array if failure)
	 */
	public function parseRequestVar($request_var) {
		// no '..'
		if (false !== strpos($request_var, '..')) {
			return array();
		}
		// only alphanumeric characters plus /, ., -, and _
		if (preg_match('#[^a-zA-Z0-9/\.\-_]#', $request_var)) {
			return array();
		}

		// testing showed regex to be marginally faster than array / string functions over 100000 reps
		// it won't make a difference in real life and regex is easier to read.
		// <ts>/<viewtype>/<name/of/view.and.dots>.<type>
		if (!preg_match('#^/?([0-9]+)/([^/]+)/(.+)$#', $request_var, $matches)) {
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
		if (!empty($this->config->dataroot) && isset($this->config->simplecache_enabled)) {
			return;
		}

		$dblink = mysql_connect($this->config->dbhost, $this->config->dbuser, $this->config->dbpass, true);
		if (!$dblink) {
			$this->send403('Cache error: unable to connect to database server');
		}

		if (!mysql_select_db($this->config->dbname, $dblink)) {
			$this->send403('Cache error: unable to connect to Elgg database');
		}

		$query = "SELECT `name`, `value` FROM {$this->config->dbprefix}datalists
				WHERE `name` IN ('dataroot', 'simplecache_enabled')";

		$result = mysql_query($query, $dblink);
		if ($result) {
			while ($row = mysql_fetch_object($result)) {
				$this->config->{$row->name} = $row->value;
			}
			mysql_free_result($result);
		}
		mysql_close($dblink);

		if (!$result || !isset($this->config->dataroot)) {
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
		return elgg_trigger_plugin_hook('simplecache:generate', $hook_type, $hook_params, $content);
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
		elgg_set_config('debug', null);

		// @todo elgg_view() checks if the page set is done (isset($CONFIG->pagesetupdone)) and
		// triggers an event if it's not. Calling elgg_view() here breaks submenus
		// (at least) because the page setup hook is called before any
		// contexts can be correctly set (since this is called before page_handler()).
		// To avoid this, lie about $CONFIG->pagehandlerdone to force
		// the trigger correctly when the first view is actually being output.
		elgg_set_config('pagesetupdone', true);

		return elgg_view($view);
	}

	/**
	 * Load the complete Elgg engine
	 *
	 * @return void
	 */
	protected function loadEngine() {
		require_once dirname(dirname(dirname(__FILE__))) . "/start.php";
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
