<?php
namespace Elgg\DeferredViews;

/**
 * This example model supplies data to the river/elements/responses view and is designed to
 * be "prepared" ahead of time to expect particular calls. This way we can batch fetch the
 * results in few queries.
 */
class RiverComments {

	const NUM_COMMENTS = 'num_comments';
	const LATEST_COMMENTS = 'latest_comments';

	protected $expectations = array();

	protected $results = array();

	protected $is_resolved = false;

	/**
	 * @param int $guid
	 * @return void
	 */
	public function prepareNumComments($guid) {
		$this->expectations[self::NUM_COMMENTS][$guid] = true;
	}

	/**
	 * @param int $guid
	 * @return int
	 */
	public function numComments($guid) {
		return $this->call(self::NUM_COMMENTS, $guid);
	}

	/**
	 * @param int $guid
	 * @return void
	 */
	public function prepareLatestComments($guid) {
		$this->expectations[self::LATEST_COMMENTS][$guid] = true;
	}

	/**
	 * @param int $guid
	 * @return \ElggComment[]
	 */
	public function latestComments($guid) {
		return $this->call(self::LATEST_COMMENTS, $guid);
	}

	protected function call($method_name, $args = null) {
		if (!$this->is_resolved) {
			$this->computeAll();
			$this->is_resolved = true;
		}
		if (isset($this->results[$method_name]) && array_key_exists($args, $this->results[$method_name])) {
			return $this->results[$method_name][$args];
		}
		$this->computeOne($method_name, $args);
		return $this->results[$method_name][$args];
	}

	/**
	 * Attempt to efficiently compute the responses to all expected API calls
	 */
	protected function computeAll() {
		// look at expectations and try to compute
		// results as quickly as possible

		if (!empty($this->expectations[self::NUM_COMMENTS])) {
			$container_guids = array_keys($this->expectations[self::NUM_COMMENTS]);

			// initialize
			foreach ($container_guids as $guid) {
				$this->results[self::NUM_COMMENTS][$guid] = 0;
			}

			// override
			$rows = elgg_get_entities(array(
				'type' => 'object',
				'subtype' => 'comment',
				'container_guid' => $container_guids,
				'group_by' => 'e.container_guid',
				'selects' => array('COUNT(1) AS cnt'),
				'callback' => false,
			));
			foreach ($rows as $row) {
				$this->results[self::NUM_COMMENTS][$row->container_guid] = (int)$row->cnt;
			}
		}

		if (!empty($this->expectations[self::LATEST_COMMENTS])) {
			$container_guids = array_keys($this->expectations[self::LATEST_COMMENTS]);

			foreach ($container_guids as $i => $guid) {
				if (isset($this->results[self::NUM_COMMENTS][$guid])
						&& $this->results[self::NUM_COMMENTS][$guid] == 0) {
					$this->results[self::LATEST_COMMENTS][$guid] = array();
					unset($container_guids[$i]);
				}
			}
		}
	}

	/**
	 * Compute and store the result of an unexpected API call
	 *
	 * @param string $method_name API method name
	 * @param string $args        Comma-separated arguments as string
	 */
	protected function computeOne($method_name, $args = '') {
		$this->results[$method_name][$args] = null;

		if ($method_name === self::NUM_COMMENTS) {
			$object = get_entity($args);
			if ($object) {
				$this->results[$method_name][$args] = $object->countComments();
			}
		} elseif ($method_name === self::LATEST_COMMENTS) {
			$this->results[$method_name][$args] = elgg_get_entities(array(
				'type' => 'object',
				'subtype' => 'comment',
				'container_guid' => $args,
				'limit' => 3,
				'order_by' => 'e.time_created desc'
			));
		}
	}
}
