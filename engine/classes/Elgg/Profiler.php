<?php
namespace Elgg;
use Elgg\Project\Paths;

/**
 * Analyzes duration of functions, queries, and processes
 *
 * @access private
 */
class Profiler {

	public $percentage_format = "%01.2f";
	public $duration_format = "%01.6f";
	public $minimum_percentage = 0.2;

	/**
	 * @var float Total time
	 */
	private $total;

	/**
	 * Return a tree of time periods from a Timer
	 *
	 * @param Timer $timer Timer object
	 * @return array
	 */
	public function buildTree(Timer $timer) {
		$times = $timer->getTimes();

		if (!isset($times[':end'])) {
			$times[':end'] = microtime();
		}

		$begin = $this->findBeginTime($times);
		$end = $this->findEndTime($times);
		$this->total = $this->diffMicrotime($begin, $end);

		return $this->analyzePeriod('', $times);
	}

	/**
	 * Turn the tree of times into a sorted list
	 *
	 * @param array  $list   Output list of times to populate
	 * @param array  $tree   Result of buildTree()
	 * @param string $prefix Prefix of period string. Leave empty.
	 * @return void
	 */
	public function flattenTree(array &$list = [], array $tree = [], $prefix = '') {
		$is_root = empty($list);

		if (isset($tree['periods'])) {
			foreach ($tree['periods'] as $period) {
				$this->flattenTree($list, $period, "{$prefix}  {$period['name']}");
			}
			unset($tree['periods']);
		}
		$tree['name'] = trim($prefix);
		$list[] = $tree;

		if ($is_root) {
			usort($list, function ($a, $b) {
				if ($a['duration'] == $b['duration']) {
					return 0;
				}
				return ($a['duration'] > $b['duration']) ? -1 : 1;
			});
		}
	}

	/**
	 * Nicely format the elapsed time values
	 *
	 * @param array $tree Result of buildTree()
	 * @return array
	 */
	public function formatTree(array $tree) {
		$tree['duration'] = sprintf($this->duration_format, $tree['duration']);
		if (isset($tree['percentage'])) {
			$tree['percentage'] = sprintf($this->percentage_format, $tree['percentage']);
		}
		if (isset($tree['periods'])) {
			$tree['periods'] = array_map([$this, 'formatTree'], $tree['periods']);
		}
		return $tree;
	}

	/**
	 * Append a SCRIPT element to the page output
	 *
	 * @param string $hook   "output"
	 * @param string $type   "page"
	 * @param string $html   Full page HTML
	 * @param array  $params Hook params
	 *
	 * @return string
	 */
	public static function handlePageOutput($hook, $type, $html, $params) {
		$profiler = new self();
		$min_percentage = _elgg_config()->profiling_minimum_percentage;
		if ($min_percentage !== null) {
			$profiler->minimum_percentage = $min_percentage;
		}

		$tree = $profiler->buildTree(_elgg_services()->timer);
		$tree = $profiler->formatTree($tree);
		$data['tree'] = $tree;
		$data['total'] = $tree['duration'] . " seconds";

		$list = [];
		$profiler->flattenTree($list, $tree);

		$root = Paths::project();
		$list = array_map(function ($period) use ($root) {
			$period['name'] = str_replace("Closure $root", "Closure ", $period['name']);
			return "{$period['percentage']}% ({$period['duration']}) {$period['name']}";
		}, $list);

		$data['list'] = $list;

		$html .= "<script>console.log(" . json_encode($data) . ");</script>";

		return $html;
	}

	/**
	 * Analyze a time period
	 *
	 * @param string $name  Period name
	 * @param array  $times Times
	 *
	 * @return array|bool False if missing begin/end time
	 */
	private function analyzePeriod($name, array $times) {
		$begin = $this->findBeginTime($times);
		$end = $this->findEndTime($times);
		if ($begin === false || $end === false) {
			return false;
		}
		unset($times[':begin'], $times[':end']);

		$total = $this->diffMicrotime($begin, $end);
		$ret = [
			'name' => $name,
			'percentage' => 100, // may be overwritten by parent
			'duration' => $total,
		];

		foreach ($times as $times_key => $period) {
			$period = $this->analyzePeriod($times_key, $period);
			if ($period === false) {
				continue;
			}
			$period['percentage'] = 100 * $period['duration'] / $this->total;
			if ($period['percentage'] < $this->minimum_percentage) {
				continue;
			}
			$ret['periods'][] = $period;
		}

		if (isset($ret['periods'])) {
			usort($ret['periods'], function ($a, $b) {
				if ($a['duration'] == $b['duration']) {
					return 0;
				}
				return ($a['duration'] > $b['duration']) ? -1 : 1;
			});
		}

		return $ret;
	}

	/**
	 * Get the microtime start time
	 *
	 * @param array $times Time periods
	 * @return string|false
	 */
	private function findBeginTime(array $times) {
		if (isset($times[':begin'])) {
			return $times[':begin'];
		}
		unset($times[':begin'], $times[':end']);
		$first = reset($times);
		if (is_array($first)) {
			return $this->findBeginTime($first);
		}
		return false;
	}

	/**
	 * Get the microtime end time
	 *
	 * @param array $times Time periods
	 * @return string|false
	 */
	private function findEndTime(array $times) {
		if (isset($times[':end'])) {
			return $times[':end'];
		}
		unset($times[':begin'], $times[':end']);
		$last = end($times);
		if (is_array($last)) {
			return $this->findEndTime($last);
		}
		return false;
	}

	/**
	 * Calculate a precise time difference.
	 *
	 * @param string $start result of microtime()
	 * @param string $end   result of microtime()
	 *
	 * @return float difference in seconds, calculated with minimum precision loss
	 */
	private function diffMicrotime($start, $end) {
		list($start_usec, $start_sec) = explode(" ", $start);
		list($end_usec, $end_sec) = explode(" ", $end);
		$diff_sec = (int) $end_sec - (int) $start_sec;
		$diff_usec = (float) $end_usec - (float) $start_usec;
		return (float) $diff_sec + $diff_usec;
	}
}
