<?php

/**
 * 
 * @todo inject plugin hook service
 *
 * @access private
 */
class Elgg_Notifications_Service {

	const QUEUE_NAME = 'notifications';

	/** @var Elgg_Util_FifoQueue */
	protected $queue;

	public function __construct(Elgg_Util_FifoQueue $queue) {
		$this->queue = $queue;
	}

	public function registerEvent($type, $subtype, array $actions = array()) {
		global $CONFIG;

		if (!isset($CONFIG->notification_events)) {
			$CONFIG->notification_events = array();
		}
		if (!isset($CONFIG->notification_events[$type])) {
			$CONFIG->notification_events[$type] = array();
		}
		if (!isset($CONFIG->notification_events[$type][$subtype])) {
			$CONFIG->notification_events[$type][$subtype] = array();
		}

		$action_list =& $CONFIG->notification_events[$type][$subtype];
		if ($actions) {
			$action_list = array_unique(array_merge($action_list, $actions));
		} elseif (!in_array('create', $action_list)) {
			$action_list[] = 'create';
		}
	}

	public function unregisterEvent($type, $subtype) {
		global $CONFIG;
		if (!isset($CONFIG->notification_events) ||
			!isset($CONFIG->notification_events[$type]) ||
			!isset($CONFIG->notification_events[$type][$subtype])) {
			return false;
		}

		unset($CONFIG->notification_events[$type][$subtype]);

		return true;
	}

	public function getEvents() {
		global $CONFIG;
		return $CONFIG->notification_events;
	}

	public function registerMethod($name) {
		global $CONFIG;

		if (!isset($CONFIG->notification_methods)) {
			$CONFIG->notification_methods = array();
		}

		$CONFIG->notification_methods[$name] = $name;
	}

	public function unregisterMethod($name) {
		global $CONFIG;

		if (!isset($CONFIG->notification_methods)) {
			return false;
		}

		if (isset($CONFIG->notification_methods[$name])) {
			unset($CONFIG->notification_methods[$name]);
			return true;
		}
		return false;
	}

	public function getMethods() {
		global $CONFIG;
		return $CONFIG->notification_methods;
	}

	public function enqueueEvent($action, $type, $object) {
		if ($object instanceof ElggData) {
			$object_type = $object->getType();
			$object_subtype = $object->getSubtype();

			$registered = false;
			global $CONFIG;
			if (isset($CONFIG->notification_events[$object_type])
				&& isset($CONFIG->notification_events[$object_type][$object_subtype])
				&& in_array($action, $CONFIG->notification_events[$object_type][$object_subtype])) {
				$registered = true;
			}

			if ($registered) {
				$params = array(
					'action' => $action,
					'object' => $object,
				);
				$registered = elgg_trigger_plugin_hook('enqueue', 'notification', $params, $registered);
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
			if (elgg_trigger_plugin_hook('send:before', 'notifications', $params, true)) {
				$this->sendNotifications($event, $subscriptions);
			}
			elgg_trigger_plugin_hook('send:after', 'notifications', $params);
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
	 * @param ElggNotificationEvent $event Notification event
	 * @return array
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
	 * @param ElggNotificationEvent $event         Notification event
	 * @param array                 $subscriptions Subscriptions for this event
	 * @return int The number of notifications handled
	 */
	protected function sendNotifications($event, $subscriptions) {

		$registeredMethods = elgg_get_config('notification_methods');
		if (!$registeredMethods) {
			return 0;
		}

		$count = 0;
		foreach ($subscriptions as $guid => $methods) {
			foreach ($methods as $method) {
				if (in_array($method, $registeredMethods)) {
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
	 * @param ElggNotificationEvent $event  The notification event
	 * @param int                   $guid   The guid of the subscriber
	 * @param string                $method The notification method
	 * @return bool
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

		$subject = elgg_echo('notification:subject', array(), $language);
		$body = elgg_echo('notification:body', array(), $language);
		$notification = new Elgg_Notifications_Notification($event->getActor(), $recipient, $language, $subject, $body);

		$type = 'notification:' . $event->getDescription();
		$notification = elgg_trigger_plugin_hook('prepare', $type, $params, $notification);

		// return true to indicate the notification has been sent
		$params = array('notification' => $notification);
		return elgg_trigger_plugin_hook('send', "notification:$method", $params, false);
	}
}