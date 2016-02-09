<?php
namespace Elgg;

/**
 * Capture timing info for profiling
 *
 * @access private
 */
class Timer {
	const MARKER_BEGIN = ':begin';
	const MARKER_END = ':end';

	private $times = [];

	/**
	 * Record the start time of a period
	 *
	 * @param string[] $keys Keys to identify period. E.g. ['startup', __FUNCTION__]
	 * @return void
	 */
	public function begin(array $keys) {
		$this->getTreeNode($keys)[self::MARKER_BEGIN] = microtime();
	}

	/**
	 * Record the end time of a period
	 *
	 * @param string[] $keys Keys to identify period. E.g. ['startup', __FUNCTION__]
	 * @return void
	 */
	public function end(array $keys) {
		$this->getTreeNode($keys)[self::MARKER_END] = microtime();
	}

	/**
	 * Has the end of the period been recorded?
	 *
	 * @param string[] $keys Keys to identify period. E.g. ['startup', __FUNCTION__]
	 * @return bool
	 */
	public function hasEnded(array $keys) {
		$node = $this->getTreeNode($keys);
		return isset($node[self::MARKER_END]);
	}

	/**
	 * Get the tree of recorded start/end times
	 *
	 * @return array
	 */
	public function getTimes() {
		return $this->times;
	}

	/**
	 * Get a reference to the period array for the given keys
	 *
	 * @param string[] $keys Keys to identify period. E.g. ['startup', __FUNCTION__]
	 * @return array
	 */
	private function &getTreeNode(array $keys) {
		$arr =& $this->times;

		foreach ($keys as $key) {
			if (!isset($arr[$key])) {
				$arr[$key] = [];
			}
			$arr =& $arr[$key];
		}

		return $arr;
	}
}
