<?php

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 * 
 * @package    Elgg.Core
 * @subpackage Notifications
 * @since      1.9.0
 */
class Elgg_Notifications_NotificationsService {

	const QUEUE_NAME = 'notifications';

	/** @var Elgg_Util_FifoQueue */
	protected $queue;

	/** @var Elgg_PluginHookService */
	protected $hooks;

	/** @var array Registered notification events */
	protected $events = array();

	/** @var array Registered notification methods */
	protected $methods = array();

	/**
	 * Constructor
	 * 
	 * @param Elgg_Util_FifoQueue $queue Queue
	 */
	public function __construct(Elgg_Util_FifoQueue $queue, Elgg_PluginHookService $hooks) {
		$this->queue = $queue;
		$this->hooks = $hooks;
	}

	/**
	 * @see elgg_register_notification_event()
	 * @access private
	 */
	public function registerEvent($type, $subtype, array $actions = array()) {

		if (!isset($this->events[$type])) {
			$this->events[$type] = array();
		}
		if (!isset($this->events[$type][$subtype])) {
			$this->events[$type][$subtype] = array();
		}

		$action_list =& $this->events[$type][$subtype];
		if ($actions) {
			$action_list = array_unique(array_merge($action_list, $actions));
		} elseif (!in_array('create', $action_list)) {
			$action_list[] = 'create';
		}
	}

	/**
	 * @see elgg_unregister_notification_event()
	 * @access private
	 */
	public function unregisterEvent($type, $subtype) {

		if (!isset($this->events[$type]) || !isset($this->events[$type][$subtype])) {
			return false;
		}

		unset($this->events[$type][$subtype]);

		return true;
	}

	/**
	 * @access private
	 */
	public function getEvents() {
		return $this->events;
	}

	/**
	 * @see elgg_register_notification_method()
	 * @access private
	 */
	public function registerMethod($name) {
		$this->methods[$name] = $name;
	}

	/**
	 * @see elgg_unregister_notification_method()
	 * @access private
	 */
	public function unregisterMethod($name) {
		if (isset($this->methods[$name])) {
			unset($this->methods[$name]);
			return true;
		}
		return false;
	}

	/**
	 * @access private
	 */
	public function getMethods() {
		return $this->methods;
	}

	/**
	 * Add a notification event to the queue
	 * 
	 * @param string   $action Action name
	 * @param string   $type   Type of the object of the action
	 * @param ElggData $object The object of the action 
	 * @return void
	 * @access private
	 */
	public function enqueueEvent($action, $type, $object) {
		if ($object instanceof ElggData) {
			$object_type = $object->getType();
			$object_subtype = $object->getSubtype();

			$registered = false;
			if (isset($this->events[$object_type])
				&& isset($this->events[$object_type][$object_subtype])
				&& in_array($action, $this->events[$object_type][$object_subtype])) {
				$registered = true;
			}

			if ($registered) {
				$params = array(
					'action' => $action,
					'object' => $object,
				);
				$registered = $this->hooks->trigger('enqueue', 'notification', $params, $registered);
			}

			if ($registered) {
				$this->queue->enqueue(new Elgg_Notifications_Event($object, $action));
			}
		}
	}

	/**
	 * Pull notification events from queue until stop time is reached
	 *
	 * @param int $stopTime The Unix time to stop sending notifications
	 * @return int The number of notification events handled
	 * @access private
	 */
	public function processQueue($stopTime) {

		$count = 0;

		// @todo grab mutex

		while (time() < $stopTime) {
			// dequeue notification event
			$event = $this->queue->dequeue();
			if (!$event) {
				break;
			}

			$subscriptions = $this->getSubscriptions($event);

			// return false to stop the default notification sender
			$params = array('event' => $event, 'subscriptions' => $subscriptions);
			if ($this->hooks->trigger('send:before', 'notifications', $params, true)) {
				$this->sendNotifications($event, $subscriptions);
			}
			$this->hooks->trigger('send:after', 'notifications', $params);
			$count++;
		}

		// release mutex

		return $count;
	}

	/**
	 * Get the subscriptions for this notification event
	 *
	 * The return array is of the form:
	 *
	 * array(
	 *     <user guid> => array('email', 'sms', 'ajax'),
	 * );
	 *
	 * @param Elgg_Notifications_Event $event Notification event
	 * @return array
	 * @access private
	 */
	protected function getSubscriptions($event) {
		// @todo not implemented
		$users = elgg_get_entities(array('type' => 'user'));
		$subscriptions = array();
		foreach ($users as $user) {
			$subscriptions[$user->guid] = array('site');
		}
		return $subscriptions;
	}

	/**
	 * Sends the notifications based on subscriptions
	 *
	 * @param Elgg_Notifications_Event $event         Notification event
	 * @param array                    $subscriptions Subscriptions for this event
	 * @return int The number of notifications handled
	 * @access private
	 */
	protected function sendNotifications($event, $subscriptions) {

		if (!$this->methods) {
			return 0;
		}

		$count = 0;
		foreach ($subscriptions as $guid => $methods) {
			foreach ($methods as $method) {
				if (in_array($method, $this->methods)) {
					if ($this->sendNotification($event, $guid, $method)) {
						$count++;
					}
				}
			}
		}
		return $count;
	}

	/**
	 * Send a notification to a subscriber
	 *
	 * @param Elgg_Notifications_Event $event  The notification event
	 * @param int                      $guid   The guid of the subscriber
	 * @param string                   $method The notification method
	 * @return bool
	 * @access private
	 */
	protected function sendNotification($event, $guid, $method) {

		$recipient = get_entity($guid);
		if (!$recipient) {
			return false;
		}
		$language = $recipient->language;
		$params = array(
			'event' => $event,
			'method' => $method,
			'recipient' => $recipient,
			'language' => $language,
		);

		// @todo what should the default subject and body be?
		$subject = elgg_echo('notification:subject', array(), $language);
		$body = elgg_echo('notification:body', array(), $language);
		$notification = new Elgg_Notifications_Notification($event->getActor(), $recipient, $language, $subject, $body);

		$type = 'notification:' . $event->getDescription();
		$notification = $this->hooks->trigger('prepare', $type, $params, $notification);

		// return true to indicate the notification has been sent
		$params = array('notification' => $notification);
		return $this->hooks->trigger('send', "notification:$method", $params, false);
	}
}