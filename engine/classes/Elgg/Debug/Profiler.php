<?php

namespace Elgg\Debug;

use Elgg\Project\Paths;
use Elgg\Timer;

/**
 * Analyzes duration of functions, queries, and processes
 *
 * @internal
 */
class Profiler {

	protected $percentage_format = "%01.2f";
	protected $duration_format = "%01.6f";
	protected $minimum_percentage = 0.2;

	/**
	 * @var float Total time
	 */
	protected $total;
	
	/**
	 * Append a SCRIPT element to the page output
	 *
	 * @param \Elgg\Hook $hook 'output', 'page'
	 *
	 * @return string
	 */
	public function __invoke(\Elgg\Hook $hook) {
		
		if (!_elgg_services()->config->enable_profiling) {
			return;
		}
		
		$profiler = new self();
		$min_percentage = _elgg_services()->config->profiling_minimum_percentage;
		if ($min_percentage !== null) {
			$profiler->minimum_percentage = $min_percentage;
		}
		
		$tree = $profiler->buildTree(_elgg_services()->timer);
		$tree = $profiler->formatTree($tree);
		$data = [
			'tree' => $tree,
			'total' => $tree['duration'] . " seconds",
		];
		
		$list = [];
		$profiler->flattenTree($list, $tree);
		
		$root = Paths::project();
		$list = array_map(function ($period) use ($root) {
			$period['name'] = str_replace("Closure $root", "Closure ", $period['name']);
			return "{$period['percentage']}% ({$period['duration']}) {$period['name']}";
		}, $list);
			
			$data['list'] = $list;
			
			$html = $hook->getValue();
			$html .= "<script>console.log(" . json_encode($data) . ");</script>";
			
			return $html;
	}
	
	/**
	 * Return a tree of time periods from a Timer
	 *
	 * @param Timer $timer Timer object
	 * @return false|array
	 */
	protected function buildTree(Timer $timer) {
		$times = $timer->getTimes();

		if (!isset($times[Timer::MARKER_END])) {
			$times[Timer::MARKER_END] = microtime(true);
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
	protected function flattenTree(array &$list = [], array $tree = [], $prefix = '') {
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
	protected function formatTree(array $tree) {
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
	 * Analyze a time period
	 *
	 * @param string $name  Period name
	 * @param array  $times Times
	 *
	 * @return false|array False if missing begin/end time
	 */
	protected function analyzePeriod($name, array $times) {
		$begin = $this->findBeginTime($times);
		$end = $this->findEndTime($times);
		if ($begin === false || $end === false) {
			return false;
		}
		$has_own_markers = isset($times[Timer::MARKER_BEGIN]) && isset($times[Timer::MARKER_BEGIN]);
		unset($times[Timer::MARKER_BEGIN], $times[Timer::MARKER_END]);

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
			if (!$has_own_markers) {
				// this is an aggregation of different non sequential timers (eg. SQL queries)
				$ret['duration'] = 0;
				foreach ($ret['periods'] as $period) {
					$ret['duration'] += $period['duration'];
				}
				$ret['percentage'] = 100 * $ret['duration'] / $this->total;
			}
			
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
	 * @return float|false
	 */
	protected function findBeginTime(array $times) {
		if (isset($times[Timer::MARKER_BEGIN])) {
			return $times[Timer::MARKER_BEGIN];
		}
		unset($times[Timer::MARKER_BEGIN], $times[Timer::MARKER_END]);
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
	 *
	 * @return float|false
	 */
	protected function findEndTime(array $times) {
		if (isset($times[Timer::MARKER_END])) {
			return $times[Timer::MARKER_END];
		}
		unset($times[Timer::MARKER_BEGIN], $times[Timer::MARKER_END]);
		$last = end($times);
		if (is_array($last)) {
			return $this->findEndTime($last);
		}
		return false;
	}

	/**
	 * Calculate a precise time difference.
	 *
	 * @param float $start result of microtime(true)
	 * @param float $end   result of microtime(true)
	 *
	 * @return float difference in seconds, calculated with minimum precision loss
	 */
	protected function diffMicrotime($start, $end) {
		return (float) $end - $start;
	}
}
