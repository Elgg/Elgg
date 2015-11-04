<?php
namespace Elgg;

use Elgg\HooksRegistrationService\Event;

/**
 * Service for Events
 *
 * @access private
 * 
 * @package    Elgg.Core
 * @subpackage Hooks
 * @since      1.9.0
 */
class EventsService extends HooksRegistrationService {

	const OPTION_STOPPABLE = 'stoppable';
	const OPTION_DEPRECATION_MESSAGE = 'deprecation_message';
	const OPTION_DEPRECATION_VERSION = 'deprecation_version';

	/**
	 * Triggers an Elgg event.
	 * 
	 * @see elgg_trigger_event
	 * @see elgg_trigger_after_event
	 * @access private
	 */
	public function trigger($name, $type, $object = null, array $options = array()) {
		$options = array_merge(array(
			self::OPTION_STOPPABLE => true,
			self::OPTION_DEPRECATION_MESSAGE => '',
			self::OPTION_DEPRECATION_VERSION => '',
		), $options);

		$has_handler = $this->hasHandler($name, $type);
		if ($has_handler && $options[self::OPTION_DEPRECATION_MESSAGE]) {
			elgg_deprecated_notice(
				$options[self::OPTION_DEPRECATION_MESSAGE],
				$options[self::OPTION_DEPRECATION_VERSION],
				2
			);
		}

		$handlers = $this->getOrderedHandlers($name, $type);
		// create Event on demand
		$event = null;
		$args = array($name, $type, $object);

		foreach ($handlers as $callback) {
			if (!is_callable($callback)) {
				$orig_callback = $callback;

				$callback = _elgg_services()->handlers->resolveCallable($callback);
				if (!$callback instanceof EventHandler) {
					if ($this->logger) {
						$msg = "Handler for event [$name, $type] is not callable nor the name of a class"
							. " that implements " . EventHandler::class . ": "
							. _elgg_services()->handlers->describeCallable($orig_callback);
						$this->logger->warn($msg);
					}
					continue;
				}
			}

			if ($callback instanceof EventHandler) {
				if ($event === null) {
					$event = new Event(elgg(), $name, $type, $object);
				}
				$return = call_user_func($callback, $event);
			} else {
				// old API
				$return = call_user_func_array($callback, $args);
			}

			if (!empty($options[self::OPTION_STOPPABLE]) && ($return === false)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Trigger a "Before event" indicating a process is about to begin.
	 *
	 * Like regular events, a handler returning false will cancel the process and false
	 * will be returned.
	 *
	 * To register for a before event, append ":before" to the event name when registering.
	 *
	 * @param string $event       The event type. The fired event type will be appended with ":before".
	 * @param string $object_type The object type
	 * @param string $object      The object involved in the event
	 *
	 * @return bool False if any handler returned false, otherwise true
	 *
	 * @see trigger
	 * @see triggerAfter
	 * @since 2.0.0
	 */
	function triggerBefore($event, $object_type, $object = null) {
		return $this->trigger("$event:before", $object_type, $object);
	}

	/**
	 * Trigger an "After event" indicating a process has finished.
	 *
	 * Unlike regular events, all the handlers will be called, their return values ignored.
	 *
	 * To register for an after event, append ":after" to the event name when registering.
	 *
	 * @param string $event       The event type. The fired event type will be appended with ":after".
	 * @param string $object_type The object type
	 * @param string $object      The object involved in the event
	 *
	 * @return true
	 *
	 * @see triggerBefore
	 * @since 2.0.0
	 */
	public function triggerAfter($event, $object_type, $object = null) {
		$options = array(
			self::OPTION_STOPPABLE => false,
		);
		return $this->trigger("$event:after", $object_type, $object, $options);
	}

	/**
	 * Trigger an event normally, but send a notice about deprecated use if any handlers are registered.
	 *
	 * @param string $event       The event type
	 * @param string $object_type The object type
	 * @param string $object      The object involved in the event
	 * @param string $message     The deprecation message
	 * @param string $version     Human-readable *release* version: 1.9, 1.10, ...
	 *
	 * @return bool
	 *
	 * @see trigger
	 */
	function triggerDeprecated($event, $object_type, $object = null, $message, $version) {
		$options = array(
			self::OPTION_DEPRECATION_MESSAGE => $message,
			self::OPTION_DEPRECATION_VERSION => $version,
		);
		return $this->trigger($event, $object_type, $object, $options);
	}
}
