<?php
namespace Elgg;
use Elgg\Debug\Inspector;

/**
 * Service for Events
 *
 * @access private
 * 
 * @package    Elgg.Core
 * @subpackage Hooks
 * @since      1.9.0
 */
class EventsService extends \Elgg\HooksRegistrationService {
	use Profilable;

	const OPTION_STOPPABLE = 'stoppable';
	const OPTION_DEPRECATION_MESSAGE = 'deprecation_message';
	const OPTION_DEPRECATION_VERSION = 'deprecation_version';

	/**
	 * @var Inspector
	 */
	private $inspector;

	/**
	 * Constructor
	 *
	 * @param Inspector $inspector Inspector
	 */
	public function __construct(Inspector $inspector = null) {
		if (!$inspector) {
			$inspector = new Inspector();
		}
		$this->inspector = $inspector;
	}

	/**
	 * {@inheritdoc}
	 */
	public function registerHandler($name, $type, $callback, $priority = 500) {
		if (in_array($type, ['member', 'friend', 'member_of_site', 'attached'])
				&& in_array($name, ['create', 'update', 'delete'])) {
			$this->logger->error("'$name, $type' event is no longer triggered. Update your event registration "
				. "to use '$name, relationship'");
		}

		if ($name === 'pagesetup' && $type === 'system') {
			static $ignore = [
				'users_pagesetup' => true,
				'profile_pagesetup' => true,
				'_elgg_friends_page_setup' => true,
				'_elgg_setup_collections_menu' => true,
				'_elgg_user_settings_menu_setup' => true,
				'developers_setup_menu' => true,
				'groups_setup_sidebar_menus' => true,
				'notifications_plugin_pagesetup' => true,
				'_elgg_admin_pagesetup' => true,
				'aalborg_theme_pagesetup' => true,
				'login_as_add_topbar_link' => true,
			];
			if (!is_string($callback) || !isset($ignore[$callback])) {
				$msg = "Event [pagesetup, system] is deprecated. Use menu or page shell hooks.";
				elgg_deprecated_notice($msg, '2.3', 2);
			}
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
	public function trigger($event, $type, $object = null, array $options = array()) {
		$options = array_merge(array(
			self::OPTION_STOPPABLE => true,
			self::OPTION_DEPRECATION_MESSAGE => '',
			self::OPTION_DEPRECATION_VERSION => '',
		), $options);

		$events = $this->hasHandler($event, $type);
		if ($events && $options[self::OPTION_DEPRECATION_MESSAGE]) {
			elgg_deprecated_notice(
				$options[self::OPTION_DEPRECATION_MESSAGE],
				$options[self::OPTION_DEPRECATION_VERSION],
				2
			);
		}

		$events = $this->getOrderedHandlers($event, $type);
		$args = array($event, $type, $object);

		foreach ($events as $callback) {
			if (!is_callable($callback)) {
				if ($this->logger) {
					$this->logger->warn("handler for event [$event, $type] is not callable: "
										. $this->inspector->describeCallable($callback));
				}
				continue;
			}

			if ($this->timer && $type === 'system' && $event !== 'shutdown') {
				$callback_as_string = $this->inspector->describeCallable($callback) . "()";

				$this->timer->begin(["[$event,$type]", $callback_as_string]);
				$return = call_user_func_array($callback, $args);
				$this->timer->end(["[$event,$type]", $callback_as_string]);
			} else {
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
