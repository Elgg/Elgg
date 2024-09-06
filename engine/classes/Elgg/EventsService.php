<?php

namespace Elgg;

use Elgg\EventsService\MethodMatcher;
use Elgg\Traits\Debug\Profilable;
use Elgg\Traits\Loggable;
use Psr\Log\LogLevel;

/**
 * Events service
 *
 * Use elgg()->events
 */
class EventsService {
	
	use Loggable;
	use Profilable;
	
	const REG_KEY_PRIORITY = 0;
	const REG_KEY_INDEX = 1;
	const REG_KEY_HANDLER = 2;
	
	const OPTION_STOPPABLE = 'stoppable';
	const OPTION_USE_TIMER = 'use_timer';
	const OPTION_TIMER_KEYS = 'timer_keys';
	const OPTION_BEGIN_CALLBACK = 'begin_callback';
	const OPTION_END_CALLBACK = 'end_callback';

	protected int $next_index = 0;
	
	protected array $ordered_handlers_cache = [];
	
	/**
	 * @var array [name][type][] = registration
	 */
	protected array $registrations = [];
	
	protected array $backups = [];

	/**
	 * Constructor
	 *
	 * @param HandlersService $handlers Handlers
	 */
	public function __construct(protected HandlersService $handlers) {
	}

	/**
	 * Triggers an Elgg event
	 *
	 * @param string $name    The event name
	 * @param string $type    The event type
	 * @param mixed  $object  The object involved in the event
	 * @param array  $options (internal) options for triggering the event
	 *
	 * @see elgg_trigger_event()
	 * @see elgg_trigger_after_event()
	 * @see elgg_trigger_before_event()
	 *
	 * @return bool
	 */
	public function trigger(string $name, string $type, $object = null, array $options = []): bool {
		$options = array_merge([
			self::OPTION_STOPPABLE => true,
		], $options);
		
		// allow for the profiling of system events (when enabled)
		if ($this->hasTimer() && $type === 'system' && $name !== 'shutdown') {
			$options[self::OPTION_USE_TIMER] = true;
			$options[self::OPTION_TIMER_KEYS] = ["[{$name},{$type}]"];
		}
		
		// get registered handlers
		$handlers = $this->getOrderedHandlers($name, $type);

		// This starts as a string, but if a handler type-hints an object we convert it on-demand inside
		// \Elgg\HandlersService::call and keep it alive during all handler calls. We do this because
		// creating objects for every triggering is expensive.
		/* @var $event Event|string */
		$event = 'event';
		$event_args = [
			$name,
			$type,
			null,
			[
				'object' => $object,
				'_elgg_sequence_id' => elgg_extract('_elgg_sequence_id', $options),
			],
		];
		foreach ($handlers as $handler) {
			list($success, $return, $event) = $this->callHandler($handler, $event, $event_args, $options);

			if (!$success) {
				continue;
			}

			if (!empty($options[self::OPTION_STOPPABLE]) && ($return === false)) {
				return false;
			}
		}

		return true;
	}
	
	/**
	 * Triggers a event that is allowed to return a mixed result
	 *
	 * @param string $name    The name of the event
	 * @param string $type    The type of the event
	 * @param mixed  $params  Supplied params for the event
	 * @param mixed  $value   The value of the event, this can be altered by registered callbacks
	 * @param array  $options (internal) options for triggering the event
	 *
	 * @return mixed
	 *
	 * @see elgg_trigger_event_results()
	 */
	public function triggerResults(string $name, string $type, array $params = [], $value = null, array $options = []) {
		// This starts as a string, but if a handler type-hints an object we convert it on-demand inside
		// \Elgg\HandlersService::call and keep it alive during all handler calls. We do this because
		// creating objects for every triggering is expensive.
		/* @var $event Event|string */
		$event = 'event';
		foreach ($this->getOrderedHandlers($name, $type) as $handler) {
			$event_args = [$name, $type, $value, $params];
			
			list($success, $return, $event) = $this->callHandler($handler, $event, $event_args, $options);
			
			if (!$success) {
				continue;
			}
			
			if ($return !== null) {
				$value = $return;
				$event->setValue($value);
			}
		}
		
		return $value;
	}

	/**
	 * Trigger a "Before event" indicating a process is about to begin.
	 *
	 * Like regular events, a handler returning false will cancel the process and false
	 * will be returned.
	 *
	 * To register for a before event, append ":before" to the event name when registering.
	 *
	 * @param string $name    The event type. The fired event type will be appended with ":before".
	 * @param string $type    The object type
	 * @param mixed  $object  The object involved in the event
	 * @param array  $options (internal) options for triggering the event
	 *
	 * @return bool False if any handler returned false, otherwise true
	 *
	 * @see EventsService::trigger()
	 * @see EventsService::triggerAfter()
	 * @since 2.0.0
	 */
	public function triggerBefore(string $name, string $type, $object = null, array $options = []): bool {
		return $this->trigger("{$name}:before", $type, $object, $options);
	}

	/**
	 * Trigger an "After event" indicating a process has finished.
	 *
	 * Unlike regular events, all the handlers will be called, their return values ignored.
	 *
	 * To register for an after event, append ":after" to the event name when registering.
	 *
	 * @param string $name    The event name. The fired event type will be appended with ":after".
	 * @param string $type    The event type
	 * @param mixed  $object  The object involved in the event
	 * @param array  $options (internal) options for triggering the event
	 *
	 * @return void
	 *
	 * @see EventsService::trigger()
	 * @see EventsService::triggerBefore()
	 * @since 2.0.0
	 */
	public function triggerAfter(string $name, string $type, $object = null, array $options = []): void {
		$options[self::OPTION_STOPPABLE] = false;
		
		$this->trigger("{$name}:after", $type, $object, $options);
	}

	/**
	 * Trigger a sequence of <event>:before, <event>, and <event>:after handlers.
	 * Allows <event>:before to terminate the sequence by returning false from a handler
	 * Allows running a callable on successful <event> before <event>:after is triggered
	 * Returns the result of the callable or bool
	 *
	 * @param string   $name     The event name
	 * @param string   $type     The event type
	 * @param mixed    $object   The object involved in the event
	 * @param callable $callable Callable to run on successful event, before event:after
	 * @param array    $options  (internal) options for triggering the event
	 *
	 * @return bool
	 */
	public function triggerSequence(string $name, string $type, $object = null, callable $callable = null, array $options = []): bool {
		// generate a unique ID to identify this sequence
		$options['_elgg_sequence_id'] = uniqid("{$name}{$type}", true);
		
		if (!$this->triggerBefore($name, $type, $object, $options)) {
			return false;
		}

		$result = $this->trigger($name, $type, $object, $options);
		if ($result === false) {
			return false;
		}

		if ($callable) {
			$result = call_user_func($callable, $object);
		}

		if ($result !== false) {
			$this->triggerAfter($name, $type, $object, $options);
		}

		return $result;
	}

	/**
	 * Trigger an sequence of <event>:before, <event>, and <event>:after handlers.
	 * Allows <event>:before to terminate the sequence by returning false from a handler
	 * Allows running a callable on successful <event> before <event>:after is triggered
	 *
	 * @param string   $name     The event name
	 * @param string   $type     The event type
	 * @param mixed    $params   Supplied params for the event
	 * @param mixed    $value    The value of the event, this can be altered by registered callbacks
	 * @param callable $callable Callable to run on successful event, before event:after
	 * @param array    $options  (internal) options for triggering the event
	 *
	 * @return mixed
	 */
	public function triggerResultsSequence(string $name, string $type, array $params = [], $value = null, callable $callable = null, array $options = []) {
		// generate a unique ID to identify this sequence
		$unique_id = uniqid("{$name}{$type}results", true);
		$options['_elgg_sequence_id'] = $unique_id;
		$params['_elgg_sequence_id'] = $unique_id;
		
		if (!$this->triggerBefore($name, $type, $params, $options)) {
			return false;
		}

		$result = $this->triggerResults($name, $type, $params, $value, $options);
		if ($result === false) {
			return false;
		}

		if ($callable) {
			$result = call_user_func($callable, $params);
		}

		if ($result !== false) {
			$this->triggerAfter($name, $type, $params, $options);
		}

		return $result;
	}

	/**
	 * Trigger an event sequence normally, but send a notice about deprecated use if any handlers are registered.
	 *
	 * @param string $name    The event name
	 * @param string $type    The event type
	 * @param mixed  $object  The object involved in the event
	 * @param string $message The deprecation message
	 * @param string $version Human-readable *release* version: 1.9, 1.10, ...
	 * @param array  $options (internal) options for triggering the event
	 *
	 * @return bool
	 *
	 * @see elgg_trigger_deprecated_event()
	 */
	public function triggerDeprecated(string $name, string $type, $object = null, string $message = '', string $version = '', array $options = []): bool {
		$message = "The '{$name}', '{$type}' event is deprecated. {$message}";
		$this->checkDeprecation($name, $type, $message, $version);
		
		return $this->trigger($name, $type, $object, $options);
	}

	/**
	 * Trigger an event sequence normally, but send a notice about deprecated use if any handlers are registered.
	 *
	 * @param string $name        The event name
	 * @param string $type        The event type
	 * @param array  $params      The parameters related to the event
	 * @param mixed  $returnvalue The return value
	 * @param string $message     The deprecation message
	 * @param string $version     Human-readable *release* version: 1.9, 1.10, ...
	 * @param array  $options     (internal) options for triggering the event
	 *
	 * @return mixed
	 *
	 * @see elgg_trigger_deprecated_event_results()
	 */
	public function triggerDeprecatedResults(string $name, string $type, array $params = [], $returnvalue = null, string $message = '', string $version = '', array $options = []) {
		$message = "The '{$name}', '{$type}' event is deprecated. {$message}";
		$this->checkDeprecation($name, $type, $message, $version);
		
		return $this->triggerResults($name, $type, $params, $returnvalue, $options);
	}
	
	/**
	 * Register a callback as a event handler.
	 *
	 * @param string   $name     The name of the event
	 * @param string   $type     The type of the event
	 * @param callable $callback The name of a valid function or an array with object and method
	 * @param int      $priority The priority - 500 is default, lower numbers called first
	 *
	 * @return bool
	 *
	 * @warning This doesn't check if a callback is valid to be called, only if it is in the
	 *          correct format as a callable.
	 */
	public function registerHandler(string $name, string $type, $callback, int $priority = 500): bool {
		if (empty($name) || empty($type) || !is_callable($callback, true)) {
			return false;
		}
		
		if (in_array($this->getLogger()->getLevel(false), [LogLevel::WARNING, LogLevel::NOTICE, LogLevel::INFO, LogLevel::DEBUG])) {
			if (!$this->handlers->isCallable($callback)) {
				$this->getLogger()->warning('Handler: ' . $this->handlers->describeCallable($callback) . ' is not callable');
			}
		}
		
		$this->registrations[$name][$type]["{$priority}_{$this->next_index}"] = [
			self::REG_KEY_PRIORITY => $priority,
			self::REG_KEY_INDEX => $this->next_index,
			self::REG_KEY_HANDLER => $callback,
		];
		$this->next_index++;
		
		unset($this->ordered_handlers_cache);
		
		return true;
	}
	
	/**
	 * Unregister a callback as an event handler.
	 *
	 * @param string   $name     The name of the event
	 * @param string   $type     The name of the type of entity (eg "user", "object" etc)
	 * @param callable $callback The PHP callback to be removed. Since 1.11, static method
	 *                           callbacks will match dynamic methods
	 *
	 * @return void
	 */
	public function unregisterHandler(string $name, string $type, $callback): void {
		if (empty($this->registrations[$name][$type])) {
			return;
		}
		
		$matcher = $this->getMatcher($callback);
		
		foreach ($this->registrations[$name][$type] as $i => $registration) {
			if ($matcher instanceof MethodMatcher) {
				if (!$matcher->matches($registration[self::REG_KEY_HANDLER])) {
					continue;
				}
			} elseif ($registration[self::REG_KEY_HANDLER] != $callback) {
				continue;
			}
			
			unset($this->registrations[$name][$type][$i]);
			unset($this->ordered_handlers_cache);
			
			return;
		}
	}
	
	/**
	 * Clears all callback registrations for an event.
	 *
	 * @param string $name The name of the event
	 * @param string $type The type of the event
	 *
	 * @return void
	 */
	public function clearHandlers(string $name, string $type): void {
		unset($this->registrations[$name][$type]);
		unset($this->ordered_handlers_cache);
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
	 * @internal
	 */
	public function getAllHandlers(): array {
		$ret = [];
		foreach ($this->registrations as $name => $types) {
			foreach ($types as $type => $registrations) {
				foreach ($registrations as $registration) {
					$priority = $registration[self::REG_KEY_PRIORITY];
					$ret[$name][$type][$priority][] = $registration[self::REG_KEY_HANDLER];
				}
			}
		}
		
		return $ret;
	}
	
	/**
	 * Is a handler registered for this specific name and type? "all" handlers are not considered.
	 *
	 * If you need to consider "all" handlers, you must check them independently, or use
	 * (bool) elgg()->events->getOrderedHandlers().
	 *
	 * @param string $name The name of the event
	 * @param string $type The type of the event
	 * @return boolean
	 */
	public function hasHandler(string $name, string $type): bool {
		return !empty($this->registrations[$name][$type]);
	}
	
	/**
	 * Returns an ordered array of handlers registered for $name and $type.
	 *
	 * @param string $name The name of the event
	 * @param string $type The type of the event
	 *
	 * @return callable[]
	 */
	public function getOrderedHandlers(string $name, string $type): array {
		$registrations = [];
		
		if (isset($this->ordered_handlers_cache[$name . $type])) {
			return $this->ordered_handlers_cache[$name . $type];
		}
		
		if (!empty($this->registrations[$name][$type])) {
			if ($name !== 'all' && $type !== 'all') {
				$registrations = $this->registrations[$name][$type];
			}
		}
		
		if (!empty($this->registrations['all'][$type])) {
			if ($type !== 'all') {
				$registrations += $this->registrations['all'][$type];
			}
		}
		
		if (!empty($this->registrations[$name]['all'])) {
			if ($name !== 'all') {
				$registrations += $this->registrations[$name]['all'];
			}
		}
		
		if (!empty($this->registrations['all']['all'])) {
			$registrations += $this->registrations['all']['all'];
		}
		
		ksort($registrations, SORT_NATURAL);
			
		$handlers = [];
		foreach ($registrations as $registration) {
			$handlers[] = $registration[self::REG_KEY_HANDLER];
		}
		
		$this->ordered_handlers_cache[$name . $type] = $handlers;
		
		return $handlers;
	}
	
	/**
	 * Create a matcher for the given callable (if it's for a static or dynamic method)
	 *
	 * @param callable $spec Callable we're creating a matcher for
	 *
	 * @return MethodMatcher|null
	 */
	protected function getMatcher($spec): ?MethodMatcher {
		if (is_string($spec) && str_contains($spec, '::')) {
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
	 * Temporarily remove all event registrations (before tests)
	 *
	 * Call backup() before your tests and restore() after.
	 *
	 * @note This behaves like a stack. You must call restore() for each backup() call.
	 *
	 * @return void
	 */
	public function backup(): void {
		$this->backups[] = $this->registrations;
		$this->registrations = [];
		unset($this->ordered_handlers_cache);
	}
	
	/**
	 * Restore backed up event registrations (after tests)
	 *
	 * @return void
	 */
	public function restore(): void {
		$backup = array_pop($this->backups);
		if (is_array($backup)) {
			$this->registrations = $backup;
		}
		
		unset($this->ordered_handlers_cache);
	}
	
	/**
	 * Check if handlers are registered on a deprecated event. If so Display a message
	 *
	 * @param string $name    the name of the event
	 * @param string $type    the type of the event
	 * @param string $message The deprecation message
	 * @param string $version Human-readable *release* version: 1.9, 1.10, ...
	 *
	 * @return void
	 */
	protected function checkDeprecation(string $name, string $type, string $message, string $version): void {
		$message = trim($message);
		if (empty($message)) {
			return;
		}
		
		if (!$this->hasHandler($name, $type)) {
			return;
		}
		
		$this->logDeprecatedMessage($message, $version);
	}
	
	/**
	 * @param callable $callable Callable
	 * @param mixed    $event    Event object
	 * @param array    $args     Event arguments
	 * @param array    $options  (internal) options for triggering the event
	 *
	 * @return array [success, result, object]
	 */
	protected function callHandler($callable, $event, array $args, array $options = []): array {
		// call a function before the actual callable
		$begin_callback = elgg_extract(self::OPTION_BEGIN_CALLBACK, $options);
		if (is_callable($begin_callback)) {
			call_user_func($begin_callback, [
				'callable' => $callable,
				'readable_callable' => $this->handlers->describeCallable($callable),
				'event' => $event,
				'arguments' => $args,
			]);
		}
		
		// time the callable function
		$use_timer = (bool) elgg_extract(self::OPTION_USE_TIMER, $options, false);
		$timer_keys = (array) elgg_extract(self::OPTION_TIMER_KEYS, $options, []);
		if ($use_timer) {
			$timer_keys[] = $this->handlers->describeCallable($callable);
			$this->beginTimer($timer_keys);
		}
		
		// execute the callable function
		$results = $this->handlers->call($callable, $event, $args);
		
		// end the timer
		if ($use_timer) {
			$this->endTimer($timer_keys);
		}
		
		// call a function after the actual callable
		$end_callback = elgg_extract(self::OPTION_END_CALLBACK, $options);
		if (is_callable($end_callback)) {
			call_user_func($end_callback, [
				'callable' => $callable,
				'readable_callable' => $this->handlers->describeCallable($callable),
				'event' => $event,
				'arguments' => $args,
				'results' => $results,
			]);
		}
		
		return $results;
	}
}
