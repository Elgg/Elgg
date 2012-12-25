<?php
class ElggDevelopersService {

	/**
	 * @var ElggDevelopersService
	 */
	private static $instance;

	/**
	 * @var ElggLogCache
	 */
	protected static $log;

	/**
	 * @var array
	 */
	protected $queryData;

	/**
	 * @return ElggDevelopersService
	 */
	static function getInstance() {
		if (!(self::$instance instanceof ElggDevelopersService)) {
			self::$instance = new ElggDevelopersService();
		}
		return self::$instance;
	}

	/**
	 * @return ElggLogCache
	 */
	function getLog() {
		if (!(self::$log instanceof ElggLogCache)) {
			self::$log = new ElggLogCache();
			// deprecated
			elgg_set_config('log_cache', self::$log);
		}
		return self::$log;
	}

	/**
	 * @param string $name name of the event on which we record profiling data
	 */
	function collectQueryData($name) {
		global $dbcalls, $DB_DELAYED_QUERIES;
		static $lastCount = 0;
		if (!is_array($this->queryData)) {
			$this->queryData = array();
		}
		$this->queryData[$name] = array($dbcalls, $dbcalls - $lastCount, count($DB_DELAYED_QUERIES));
		$lastCount = $dbcalls;
	}

	/**
	 * Attaches SQL profiling data to the foot view output.
	 * @return string
	 */
	function displayQueryData($hook, $type, $returnvalue, $params) {
		global $START_MICROTIME;
		$this->collectQueryData('foot');

		$output = '<pre>';
		foreach ($this->queryData as $name => $data) {
			$output .= elgg_echo('developers:query_count:output', array_merge(array($name), (array)$data))."\n";
		}
		$output .= elgg_echo('developers:total_time:output', array(microtime(true) - $START_MICROTIME));
		$output .= '</pre>';

		$returnvalue .= $output;
		return $returnvalue;
	}

	/**
	 * Clear all the strings so the raw descriptor strings are displayed
	 */
	function clearStrings() {
		global $CONFIG;

		$language = get_language();
		$CONFIG->translations[$language] = array();
		$CONFIG->translations['en'] = array();
	}

	/**
	 * Post-process a view to add wrapper comments to it
	 */
	function wrapViews($hook, $type, $result, $params) {
		if (elgg_get_viewtype() != "default") {
			return;
		}

		$excluded_bases = array('css', 'js', 'input', 'output', 'embed', 'icon',);

		$excluded_views = array(
			'page/default',
			'page/admin',
			'page/elements/head',
		);

		$view = $params['view'];

		$view_hierarchy = explode('/',$view);
		if (in_array($view_hierarchy[0], $excluded_bases)) {
			return;
		}

		if (in_array($view, $excluded_views)) {
			return;
		}

		if ($result) {
			$result = "<!-- developers:begin $view -->$result<!-- developers:end $view -->";
		}

		return $result;
	}

	/**
	 * Log the events and plugin hooks
	 */
	function logEvents($name, $type) {

		// filter out some very common events
		if ($name == 'view' || $name == 'display' || $name == 'log' || $name == 'debug') {
			return;
		}
		if ($name == 'session:get' || $name == 'validate') {
			return;
		}

		$stack = debug_backtrace();
		if ($stack[2]['function'] == 'elgg_trigger_event') {
			$event_type = 'Event';
		} else {
			$event_type = 'Plugin hook';
		}
		$function = $stack[3]['function'] . '()';
		if ($function == 'require_once' || $function == 'include_once') {
			$function = $stack[3]['file'];
		}

		$msg = elgg_echo('developers:event_log_msg', array(
			$event_type,
			$name,
			$type,
			$function,
		));
		elgg_dump($msg, false, 'WARNING');

		unset($stack);
	}

	/**
	 * Serve the theme preview pages
	 *
	 * @param array $page
	 * @return bool
	 */
	function themePreviewController($page) {
		if (!isset($page[0])) {
			forward('theme_preview/general');
		}

		$pages = array(
			'buttons',
			'components',
			'forms',
			'grid',
			'icons',
			'modules',
			'navigation',
			'typography',
		);

		foreach ($pages as $page_name) {
			elgg_register_menu_item('page', array(
				'name' => $page_name,
				'text' => elgg_echo("theme_preview:$page_name"),
				'href' => "theme_preview/$page_name",
			));
		}

		$title = elgg_echo("theme_preview:{$page[0]}");
		$body =  elgg_view("theme_preview/{$page[0]}");

		$layout = elgg_view_layout('one_sidebar', array(
			'title' => $title,
			'content' => $body,
		));

		echo elgg_view_page($title, $layout, 'theme_preview');
		return true;
	}
}