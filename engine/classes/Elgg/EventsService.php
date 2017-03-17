<?php
namespace Elgg;

use Elgg\HooksRegistrationService\Hook;
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
	use Profilable;

	const OPTION_STOPPABLE = 'stoppable';
	const OPTION_DEPRECATION_MESSAGE = 'deprecation_message';
	const OPTION_DEPRECATION_VERSION = 'deprecation_version';

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
	 * Triggers an Elgg event.
	 *
	 * @see elgg_trigger_event
	 * @see elgg_trigger_after_event
	 * @access private
	 */
	public function trigger($name, $type, $object = null, array $options = []) {
		$options = array_merge([
			self::OPTION_STOPPABLE => true,
			self::OPTION_DEPRECATION_MESSAGE => '',
			self::OPTION_DEPRECATION_VERSION => '',
		], $options);

		$handlers = $this->hasHandler($name, $type);
		if ($handlers && $options[self::OPTION_DEPRECATION_MESSAGE]) {
			_elgg_services()->deprecation->sendNotice(
				$options[self::OPTION_DEPRECATION_MESSAGE],
				$options[self::OPTION_DEPRECATION_VERSION],
				2
			);
		}

		$handlers = $this->getOrderedHandlers($name, $type);
		$handler_svc = _elgg_services()->handlers;

		// This starts as a string, but if a handler type-hints an object we convert it on-demand inside
		// \Elgg\HandlersService::call and keep it alive during all handler calls. We do this because
		// creating objects for every triggering is expensive.
		$event = 'event';
		/* @var Event|string */

		foreach ($handlers as $handler) {
			$handler_description = false;
			if ($this->timer && $type === 'system' && $name !== 'shutdown') {
				$handler_description = $handler_svc->describeCallable($handler) . "()";
				$this->timer->begin(["[$name,$type]", $handler_description]);
			}

			list($success, $return, $event) = $handler_svc->call($handler, $event, [$name, $type, $object]);

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
		$options = [
			self::OPTION_STOPPABLE => false,
		];
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
	function triggerDeprecated($event, $object_type, $object = null, $message = null, $version = null) {
		$options = [
			self::OPTION_DEPRECATION_MESSAGE => $message,
			self::OPTION_DEPRECATION_VERSION => $version,
		];
		return $this->trigger($event, $object_type, $object, $options);
	}
}
