<?php
namespace Elgg\Notifications;

use ElggEntity;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 * 
 * @package    Elgg.Core
 * @subpackage Notifications
 * @since      1.9.0
 */
class NotificationsService {

	const QUEUE_NAME = 'notifications';

	/** @var \Elgg\Notifications\SubscriptionsService */
	protected $subscriptions;

	/** @var \Elgg\Queue\Queue */
	protected $queue;

	/** @var \Elgg\PluginHooksService */
	protected $hooks;

	/** @var \ElggSession */
	protected $session;

	/** @var array Registered notification events */
	protected $events = array();

	/** @var array Registered notification methods */
	protected $methods = array();

	/** @var array Deprecated notification handlers */
	protected $deprHandlers = array();

	/** @var array Deprecated message subjects */
	protected $deprSubjects = array();

	/**
	 * Constructor
	 *
	 * @param \Elgg\Notifications\SubscriptionsService $subscriptions Subscription service
	 * @param \Elgg\Queue\Queue                        $queue         Queue
	 * @param \Elgg\PluginHooksService                 $hooks         Plugin hook service
	 * @param \ElggSession                             $session       Session service
	 */
	public function __construct(\Elgg\Notifications\SubscriptionsService $subscriptions,
			\Elgg\Queue\Queue $queue, \Elgg\PluginHooksService $hooks, \ElggSession $session) {
		$this->subscriptions = $subscriptions;
		$this->queue = $queue;
		$this->hooks = $hooks;
		$this->session = $session;
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
	 * @param \ElggData $object The object of the action 
	 * @return void
	 * @access private
	 */
	public function enqueueEvent($action, $type, $object) {
		if ($object instanceof \ElggData) {
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
				$this->queue->enqueue(new \Elgg\Notifications\Event($object, $action));
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

		$this->subscriptions->methods = $this->methods;

		$count = 0;

		// @todo grab mutex
		
		$ia = $this->session->setIgnoreAccess(true);

		while (time() < $stopTime) {
			// dequeue notification event
			$event = $this->queue->dequeue();
			if (!$event) {
				break;
			}

			// test for usage of the deprecated override hook
			if ($this->existsDeprecatedNotificationOverride($event)) {
				continue;
			}

			$subscriptions = $this->subscriptions->getSubscriptions($event);

			// return false to stop the default notification sender
			$params = array('event' => $event, 'subscriptions' => $subscriptions);
			if ($this->hooks->trigger('send:before', 'notifications', $params, true)) {
				$this->sendNotifications($event, $subscriptions);
			}
			$this->hooks->trigger('send:after', 'notifications', $params);
			$count++;
		}

		// release mutex

		$this->session->setIgnoreAccess($ia);

		return $count;
	}

	/**
	 * Sends the notifications based on subscriptions
	 *
	 * @param \Elgg\Notifications\Event $event         Notification event
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
	 * @param \Elgg\Notifications\Event $event  The notification event
	 * @param int                      $guid   The guid of the subscriber
	 * @param string                   $method The notification method
	 * @return bool
	 * @access private
	 */
	protected function sendNotification(\Elgg\Notifications\Event $event, $guid, $method) {

		$recipient = get_user($guid);
		if (!$recipient || $recipient->isBanned()) {
			return false;
		}

		// don't notify the creator of the content
		if ($recipient->getGUID() == $event->getActorGUID()) {
			return false;
		}

		$actor = $event->getActor();
		$object = $event->getObject();
		if (!$actor || !$object) {
			return false;
		}

		if (($object instanceof ElggEntity) && !has_access_to_entity($object, $recipient)) {
			return false;
		}

		$language = $recipient->language;
		$params = array(
			'event' => $event,
			'method' => $method,
			'recipient' => $recipient,
			'language' => $language,
			'object' => $object,
		);

		$subject = _elgg_services()->translator->translate('notification:subject', array($actor->name), $language);
		$body = _elgg_services()->translator->translate('notification:body', array($object->getURL()), $language);
		$notification = new \Elgg\Notifications\Notification($event->getActor(), $recipient, $language, $subject, $body, '', $params);

		$type = 'notification:' . $event->getDescription();
		if ($this->hooks->hasHandler('prepare', $type)) {
			$notification = $this->hooks->trigger('prepare', $type, $params, $notification);
		} else {
			// pre Elgg 1.9 notification message generation
			$notification = $this->getDeprecatedNotificationBody($notification, $event, $method);
		}

		if ($this->hooks->hasHandler('send', "notification:$method")) {
			// return true to indicate the notification has been sent
			$params = array(
				'notification' => $notification,
				'event' => $event,
			);
			return $this->hooks->trigger('send', "notification:$method", $params, false);
		} else {
			// pre Elgg 1.9 notification handler
			$userGuid = $notification->getRecipientGUID();
			$senderGuid = $notification->getSenderGUID();
			$subject = $notification->subject;
			$body = $notification->body;
			$params = $notification->params;
			return (bool)_elgg_notify_user($userGuid, $senderGuid, $subject, $body, $params, array($method));
		}
	}

	/**
	 * Register a deprecated notification handler
	 * 
	 * @param string $method  Method name
	 * @param string $handler Handler callback
	 * @return void
	 */
	public function registerDeprecatedHandler($method, $handler) {
		$this->deprHandlers[$method] = $handler;
	}

	/**
	 * Get a deprecated notification handler callback
	 * 
	 * @param string $method Method name
	 * @return callback|null
	 */
	public function getDeprecatedHandler($method) {
		if (isset($this->deprHandlers[$method])) {
			return $this->deprHandlers[$method];
		} else {
			return null;
		}
	}

	/**
	 * Provides a way to incrementally wean Elgg's notifications code from the
	 * global $NOTIFICATION_HANDLERS
	 * 
	 * @return array
	 */
	public function getMethodsAsDeprecatedGlobal() {
		$data = array();
		foreach ($this->methods as $method) {
			$data[$method] = 'empty';
		}
		return $data;
	}

	/**
	 * Get the notification body using a pre-Elgg 1.9 plugin hook
	 * 
	 * @param \Elgg\Notifications\Notification $notification Notification
	 * @param \Elgg\Notifications\Event        $event        Event
	 * @param string                           $method       Method
	 * @return \Elgg\Notifications\Notification
	 */
	protected function getDeprecatedNotificationBody(\Elgg\Notifications\Notification $notification, \Elgg\Notifications\Event $event, $method) {
		$entity = $event->getObject();
		$params = array(
			'entity' => $entity,
			'to_entity' => $notification->getRecipient(),
			'method' => $method,
		);
		$subject = $this->getDeprecatedNotificationSubject($entity->getType(), $entity->getSubtype());
		$string = $subject . ": " . $entity->getURL();
		$body = $this->hooks->trigger('notify:entity:message', $entity->getType(), $params, $string);

		if ($subject) {
			$notification->subject = $subject;
			$notification->body = $body;
		}

		return $notification;
	}

	/**
	 * Set message subject for deprecated notification code
	 * 
	 * @param string $type    Entity type
	 * @param string $subtype Entity subtype
	 * @param string $subject Subject line
	 * @return void
	 */
	public function setDeprecatedNotificationSubject($type, $subtype, $subject) {
		if ($type == '') {
			$type = '__BLANK__';
		}
		if ($subtype == '') {
			$subtype = '__BLANK__';
		}

		if (!isset($this->deprSubjects[$type])) {
			$this->deprSubjects[$type] = array();
		}

		$this->deprSubjects[$type][$subtype] = $subject;
	}

	/**
	 * Get the deprecated subject
	 * 
	 * @param string $type    Entity type
	 * @param string $subtype Entity subtype
	 * @return string
	 */
	protected function getDeprecatedNotificationSubject($type, $subtype) {
		if ($type == '') {
			$type = '__BLANK__';
		}
		if ($subtype == '') {
			$subtype = '__BLANK__';
		}

		if (!isset($this->deprSubjects[$type])) {
			return '';
		}

		if (!isset($this->deprSubjects[$type][$subtype])) {
			return '';
		}

		return $this->deprSubjects[$type][$subtype];
	}

	/**
	 * Is someone using the deprecated override
	 * 
	 * @param \Elgg\Notifications\Event $event Event
	 * @return boolean
	 */
	protected function existsDeprecatedNotificationOverride(\Elgg\Notifications\Event $event) {
		$entity = $event->getObject();
		if (!elgg_instanceof($entity)) {
			return false;
		}
		$params = array(
			'event' => $event->getAction(),
			'object_type' => $entity->getType(),
			'object' => $entity,
		);
		$hookresult = $this->hooks->trigger('object:notifications', $entity->getType(), $params, false);
		if ($hookresult === true) {
			elgg_deprecated_notice("Using the plugin hook 'object:notifications' has been deprecated by the hook 'send:before', 'notifications'", 1.9);
			return true;
		} else {
			return false;
		}
	}
}
