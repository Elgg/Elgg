<?php
namespace Elgg;

/**
 * Events service
 *
 * Use elgg()->events
 */
class EventsService extends HooksRegistrationService {
	use Profilable;

	const OPTION_STOPPABLE = 'stoppable';

	/**
	 * @var HandlersService
	 */
	private $handlers;

	/**
	 * Constructor
	 *
	 * @param HandlersService $handlers Handlers
	 * @access private
	 * @internal
	 */
	public function __construct(HandlersService $handlers) {
		$this->handlers = $handlers;
	}

	/**
	 * Get the handlers service in use
	 *
	 * @return HandlersService
	 * @access private
	 * @internal
	 */
	public function getHandlersService() {
		return $this->handlers;
	}

	/**
	 * {@inheritdoc}
	 */
	public function registerHandler($name, $type, $callback, $priority = 500) {
		if (in_array($type, ['member', 'friend', 'attached'])
				&& in_array($name, ['create', 'update', 'delete'])) {
			_elgg_services()->logger->error("'$name, $type' event is no longer triggered. "
				. "Update your event registration to use '$name, relationship'");
		}

		return parent::registerHandler($name, $type, $callback, $priority);
	}

	/**
	 * Triggers an Elgg event
	 *
	 * @param string $event       The event type
	 * @param string $object_type The object type
	 * @param string $object      The object involved in the event
	 * @param array  $options     (internal) options for triggering the event
	 *
	 * @see elgg_trigger_event()
	 * @see elgg_trigger_after_event()
	 * @see elgg_trigger_before_event()
	 */
	public function trigger($name, $type, $object = null, array $options = []) {
		$options = array_merge([
			self::OPTION_STOPPABLE => true,
		], $options);

		// check for deprecation
		$this->checkDeprecation($name, $type, $options);

		// get registered handlers
		$handlers = $this->getOrderedHandlers($name, $type);

		// This starts as a string, but if a handler type-hints an object we convert it on-demand inside
		// \Elgg\HandlersService::call and keep it alive during all handler calls. We do this because
		// creating objects for every triggering is expensive.
		$event = 'event';
		/* @var Event|string */

		foreach ($handlers as $handler) {
			$handler_description = false;
			if ($this->timer && $type === 'system' && $name !== 'shutdown') {
				$handler_description = $this->handlers->describeCallable($handler) . "()";
				$this->timer->begin(["[$name,$type]", $handler_description]);
			}

			list($success, $return, $event) = $this->handlers->call($handler, $event, [$name, $type, $object]);

			if ($handler_description) {
				$this->timer->end(["[$name,$type]", $handler_description]);
			}

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
	 * @see EventsService::trigger()
	 * @see EventsService::triggerAfter()
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
	 * @see EventsService::trigger()
	 * @see EventsService::triggerBefore()
	 * @since 2.0.0
	 */
	public function triggerAfter($event, $object_type, $object = null) {
		$options = [
			self::OPTION_STOPPABLE => false,
		];
		return $this->trigger("$event:after", $object_type, $object, $options);
	}

	/**
	 * Trigger an sequence of <event>:before, <event>, and <event>:after handlers.
	 * Allows <event>:before to terminate the sequence by returning false from a handler
	 * Allows running a callable on successful <event> before <event>:after is triggered
	 * Returns the result of the callable or bool
	 *
	 * @param string   $event       The event type
	 * @param string   $object_type The object type
	 * @param string   $object      The object involved in the event
	 * @param callable $callable    Callable to run on successful event, before event:after
	 * @return mixed
	 */
	public function triggerSequence($event, $object_type, $object = null, callable $callable = null) {
		if (!$this->triggerBefore($event, $object_type, $object)) {
			return false;
		}

		$result = $this->trigger($event, $object_type, $object);
		if (!$result) {
			return false;
		}

		if ($callable) {
			$result = call_user_func($callable, $object);
		}

		$this->triggerAfter($event, $object_type, $object);

		return $result;
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
	 * @see EventsService::trigger()
	 * @see elgg_trigger_deprecated_event()
	 */
	function triggerDeprecated($event, $object_type, $object = null, $message = null, $version = null) {
		$options = [
			self::OPTION_DEPRECATION_MESSAGE => $message,
			self::OPTION_DEPRECATION_VERSION => $version,
		];
		return $this->trigger($event, $object_type, $object, $options);
	}
}
