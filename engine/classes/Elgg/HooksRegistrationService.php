<?php
namespace Elgg;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @access private
 *
 * @package    Elgg.Core
 * @subpackage Hooks
 * @since      1.9.0
 */
abstract class HooksRegistrationService {

	const REG_KEY_PRIORITY = 0;
	const REG_KEY_INDEX = 1;
	const REG_KEY_HANDLER = 2;

	/**
	 * @var int
	 */
	private $next_index = 0;

	/**
	 * @var array [name][type][] = registration
	 */
	private $registrations = [];

	/**
	 * @var array
	 */
	private $backups = [];

	/**
	 * @var \Elgg\Logger
	 */
	protected $logger;

	/**
	 * Set a logger instance, e.g. for reporting uncallable handlers
	 *
	 * @param \Elgg\Logger $logger The logger
	 * @return self
	 */
	public function setLogger(\Elgg\Logger $logger = null) {
		$this->logger = $logger;
		return $this;
	}
	
	/**
	 * Registers a handler.
	 *
	 * @warning This doesn't check if a callback is valid to be called, only if it is in the
	 *          correct format as a callable.
	 *
	 * @access private
	 */
	public function registerHandler($name, $type, $callback, $priority = 500) {
		if (empty($name) || empty($type) || !is_callable($callback, true)) {
			return false;
		}
		
		if (($name == 'view' || $name == 'view_vars') && $type != 'all') {
			$type = _elgg_services()->views->canonicalizeViewName($type);
		}

		$this->registrations[$name][$type][] = [
			self::REG_KEY_PRIORITY => $priority,
			self::REG_KEY_INDEX => $this->next_index,
			self::REG_KEY_HANDLER => $callback,
		];
		$this->next_index++;

		return true;
	}
	
	/**
	 * Unregister a handler
	 *
	 * @param string   $name
	 * @param string   $type
	 * @param callable $callback
	 *
	 * @return bool
	 * @access private
	 */
	public function unregisterHandler($name, $type, $callback) {
		if (($name == 'view' || $name == 'view_vars') && $type != 'all') {
			$type = _elgg_services()->views->canonicalizeViewName($type);
		}

		if (empty($this->registrations[$name][$type])) {
			return false;
		}

		$matcher = $this->getMatcher($callback);

		foreach ($this->registrations[$name][$type] as $i => $registration) {

			if ($matcher) {
				if (!$matcher->matches($registration[self::REG_KEY_HANDLER])) {
					continue;
				}
			} else {
				if ($registration[self::REG_KEY_HANDLER] != $callback) {
					continue;
				}
			}

			unset($this->registrations[$name][$type][$i]);
			return true;
		}

		return false;
	}
	
	/**
	 * Clears all handlers for a specific hook
	 *
	 * @param string   $name
	 * @param string   $type
	 *
	 * @return void
	 * @access private
	 */
	public function clearHandlers($name, $type) {
		unset($this->registrations[$name][$type]);
	}

	/**
	 * Returns all registered handlers as array(
	 * $name => array(
	 *     $type => array(
	 *         $priority => array(
	 *             callback,
	 *             callback,
	 *         )
	 *     )
	 * )
	 *
	 * @access private
	 * @return array
	 */
	public function getAllHandlers() {
		$ret = [];
		foreach ($this->registrations as $name => $types) {
			foreach ($types as $type => $registrations) {
				foreach ($registrations as $registration) {
					$priority = $registration[self::REG_KEY_PRIORITY];
					$handler = $registration[self::REG_KEY_HANDLER];
					$ret[$name][$type][$priority][] = $handler;
				}
			}
		}

		return $ret;
	}

	/**
	 * Is a handler registered for this specific name and type? "all" handlers are not considered.
	 *
	 * If you need to consider "all" handlers, you must check them independently, or use
	 * (bool)elgg_get_ordered_hook_handlers().
	 *
	 * @param string $name The name of the hook
	 * @param string $type The type of the hook
	 * @return boolean
	 */
	public function hasHandler($name, $type) {
		return !empty($this->registrations[$name][$type]);
	}

	/**
	 * Returns an ordered array of handlers registered for $name and $type.
	 *
	 * @param string $name The name of the hook
	 * @param string $type The type of the hook
	 * @return array
	 * @see \Elgg\HooksRegistrationService::getAllHandlers()
	 *
	 * @access private
	 */
	public function getOrderedHandlers($name, $type) {
		$registrations = [];
		
		if (!empty($this->registrations[$name][$type])) {
			if ($name !== 'all' && $type !== 'all') {
				array_splice($registrations, count($registrations), 0, $this->registrations[$name][$type]);
			}
		}
		if (!empty($this->registrations['all'][$type])) {
			if ($type !== 'all') {
				array_splice($registrations, count($registrations), 0, $this->registrations['all'][$type]);
			}
		}
		if (!empty($this->registrations[$name]['all'])) {
			if ($name !== 'all') {
				array_splice($registrations, count($registrations), 0, $this->registrations[$name]['all']);
			}
		}
		if (!empty($this->registrations['all']['all'])) {
			array_splice($registrations, count($registrations), 0, $this->registrations['all']['all']);
		}

		usort($registrations, function ($a, $b) {
			// priority first
			if ($a[self::REG_KEY_PRIORITY] < $b[self::REG_KEY_PRIORITY]) {
				return -1;
			}
			if ($a[self::REG_KEY_PRIORITY] > $b[self::REG_KEY_PRIORITY]) {
				return 1;
			}
			// then insertion order
			return ($a[self::REG_KEY_INDEX] < $b[self::REG_KEY_INDEX]) ? -1 : 1;
		});

		$handlers = [];
		foreach ($registrations as $registration) {
			$handlers[] = $registration[self::REG_KEY_HANDLER];
		}

		return $handlers;
	}

	/**
	 * Create a matcher for the given callable (if it's for a static or dynamic method)
	 *
	 * @param callable $spec Callable we're creating a matcher for
	 *
	 * @return MethodMatcher|null
	 */
	protected function getMatcher($spec) {
		if (is_string($spec) && false !== strpos($spec, '::')) {
			list ($type, $method) = explode('::', $spec, 2);
			return new MethodMatcher($type, $method);
		}

		if (!is_array($spec) || empty($spec[0]) || empty($spec[1]) || !is_string($spec[1])) {
			return null;
		}

		if (is_object($spec[0])) {
			$spec[0] = get_class($spec[0]);
		}

		if (!is_string($spec[0])) {
			return null;
		}

		return new MethodMatcher($spec[0], $spec[1]);
	}

	/**
	 * Temporarily remove all event/hook registrations (before tests)
	 *
	 * Call backup() before your tests and restore() after.
	 *
	 * @note This behaves like a stack. You must call restore() for each backup() call.
	 *
	 * @return void
	 * @see restore
	 * @access private
	 * @internal
	 */
	public function backup() {
		$this->backups[] = $this->registrations;
		$this->registrations = [];
	}

	/**
	 * Restore backed up event/hook registrations (after tests)
	 *
	 * @return void
	 * @see backup
	 * @access private
	 * @internal
	 */
	public function restore() {
		$backup = array_pop($this->backups);
		if (is_array($backup)) {
			$this->registrations = $backup;
		}
	}
}
