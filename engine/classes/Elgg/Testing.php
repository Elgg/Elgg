<?php
namespace Elgg;

use Elgg\Di\ServiceProvider;

/**
 * @access private
 */
class Testing {

	/**
	 * @var ServiceProvider
	 */
	protected $services;

	/**
	 * Constructor
	 *
	 * @param ServiceProvider $services
	 */
	public function __construct(ServiceProvider $services) {
		$this->services = $services;
	}

	/**
	 * Execute code while capturing all events and hooks
	 *
	 * @param callable $operation Your code
	 *
	 * @return array With keys 'hooks' and 'events'
	 */
	public function captureEventsAndHooks(callable $operation) {
		$events = $this->services->events;
		$hooks = $this->services->hooks;
		$captured = array();

		$event_handler = function ($event, $type, $object) use (&$captured) {
			$captured['events'][$event][$type][] = $object;
		};
		$hook_handler = function ($hook, $type, $value, $params) use (&$captured) {
			$captured['hooks'][$hook][$type][] = array(
				'value' => $value,
				'params' => $params,
			);
		};

		$events->registerHandler('all', 'all', $event_handler, 0);
		$hooks->registerHandler('all', 'all', $hook_handler, 0);

		$operation();

		// cleanup
		$events->unregisterHandler('all', 'all', $event_handler);
		$hooks->unregisterHandler('all', 'all', $hook_handler);
		return $captured;
	}

	/**
	 * Execute code while capturing (and suppressing) elgg_log() calls (via the [debug, log] hook)
	 *
	 * @param callable $operation Your code
	 *
	 * @return array Hook parameters captured from the [debug, log] hook.
	 */
	public function captureLogs(callable $operation) {
		$hooks = $this->services->hooks;
		$logger = $this->services->logger;
		$captured = array();

		$hook_handler = function ($hook, $type, $value, $params) use (&$captured) {
			$captured[] = $params;
			return false;
		};

		// log everything (lower the logging threshold)
		$log_level = $logger->getLevel();
		$logger->setLevel(Logger::INFO);
		$hooks->registerHandler('debug', 'log', $hook_handler, 0);

		$operation();

		// cleanup
		$hooks->unregisterHandler('debug', 'log', $hook_handler);
		$logger->setLevel($log_level);

		return $captured;
	}

	/**
	 * Execute code while capturing deprecation warnings
	 *
	 * @param callable $operation
	 *
	 * @return array Deprecation warning counts keyed by version and message
	 */
	public function captureDeprecations(callable $operation) {
		return $this->findDeprecations($this->captureLogs($operation));
	}

	/**
	 * Extract deprecation warnings from captured logs
	 *
	 * @param array $captured_logs Output of captureLogs()
	 *
	 * @return array Deprecation warning counts keyed by version and message
	 */
	public function findDeprecations(array $captured_logs) {
		$deprecations = array();
		foreach ($captured_logs as $log) {
			if (preg_match('~^WARNING\\: Deprecated in ([^\\:]+)\\: (.*?) Called from \\[~', $log['msg'], $m)) {
				$deprecations[$m[1]][$m[2]] = isset($deprecations[$m[1]][$m[2]]) ? $deprecations[$m[1]][$m[2]] + 1 : 1;
			}
		}
		return $deprecations;
	}
}
