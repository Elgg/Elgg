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
		global $dbcalls, $START_MICROTIME;
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

}