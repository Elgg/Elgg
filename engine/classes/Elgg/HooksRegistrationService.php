<?php

namespace Elgg;

use Elgg\Traits\Loggable;
use Psr\Log\LogLevel;

/**
 * Base class for events and hooks
 *
 * @internal
 */
abstract class HooksRegistrationService {

	use Loggable;
	
	const REG_KEY_PRIORITY = 0;
	const REG_KEY_INDEX = 1;
	const REG_KEY_HANDLER = 2;
	
	const OPTION_DEPRECATION_MESSAGE = 'deprecation_message';
	const OPTION_DEPRECATION_VERSION = 'deprecation_version';

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
	 * Register a callback as a plugin hook handler.
	 *
	 * @param string   $name     The name of the hook
	 * @param string   $type     The type of the hook
	 * @param callable $callback The name of a valid function or an array with object and method
	 * @param int      $priority The priority - 500 is default, lower numbers called first
	 *
	 * @return bool
	 *
	 * @warning This doesn't check if a callback is valid to be called, only if it is in the
	 *          correct format as a callable.
	 * @see elgg_register_plugin_hook_handler()
	 */
	public function registerHandler($name, $type, $callback, $priority = 500) {
		if (empty($name) || empty($type) || !is_callable($callback, true)) {
			return false;
		}
		
		$services = _elgg_services();
		if (in_array($this->getLogger()->getLevel(false), [LogLevel::WARNING, LogLevel::NOTICE, LogLevel::INFO, LogLevel::DEBUG])) {
			if (!$services->handlers->isCallable($callback)) {
				$this->getLogger()->warning('Handler: ' . $services->handlers->describeCallable($callback) . ' is not callable');
			}
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
	 * Unregister a callback as a plugin hook of event handler.
	 *
	 * @param string   $name     The name of the hook/event
	 * @param string   $type     The name of the type of entity (eg "user", "object" etc)
	 * @param callable $callback The PHP callback to be removed. Since 1.11, static method
	 *                           callbacks will match dynamic methods
	 *
	 * @return bool
	 *
	 * @see elgg_unregister_plugin_hook_handler()
	 * @see elgg_unregister_event_handler()
	 */
	public function unregisterHandler($name, $type, $callback) {
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
	 * Clears all callback registrations for a plugin hook.
	 *
	 * @param string $name The name of the hook
	 * @param string $type The type of the hook
	 *
	 * @return void
	 *
	 * @see elgg_clear_plugin_hook_handlers()
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
	 * (bool) elgg()->hooks->getOrderedHandlers().
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
	 * @return callable[]
	 * @see \Elgg\HooksRegistrationService::getAllHandlers()
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
	 * @see HooksRegistrationService::restore()
	 */
	public function backup() {
		$this->backups[] = $this->registrations;
		$this->registrations = [];
	}

	/**
	 * Restore backed up event/hook registrations (after tests)
	 *
	 * @return void
	 * @see HooksRegistrationService::backup()
	 */
	public function restore() {
		$backup = array_pop($this->backups);
		if (is_array($backup)) {
			$this->registrations = $backup;
		}
	}
	
	/**
	 * Check if handlers are registered on a deprecated hook/event. If so Display a message
	 *
	 * @param string $name    the name of the hook/event
	 * @param string $type    the type of the hook/event
	 * @param array  $options deprecation options
	 *
	 * @return void
	 */
	protected function checkDeprecation($name, $type, array $options = []) {
		$options = array_merge([
			self::OPTION_DEPRECATION_MESSAGE => '',
			self::OPTION_DEPRECATION_VERSION => '',
		], $options);
		
		$handlers = $this->hasHandler($name, $type);
		if (!$handlers || !$options[self::OPTION_DEPRECATION_MESSAGE]) {
			return;
		}
		
		$this->logDeprecatedMessage(
			$options[self::OPTION_DEPRECATION_MESSAGE],
			$options[self::OPTION_DEPRECATION_VERSION]
		);
	}
}
