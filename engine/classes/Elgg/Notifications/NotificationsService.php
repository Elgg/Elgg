<?php

namespace Elgg\Notifications;

use Elgg\EventsService;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Queue\Queue;
use Elgg\Traits\Loggable;

/**
 * Notifications service
 *
 * @internal
 * @since 1.9.0
 */
class NotificationsService {

	use Loggable;
	
	/** @var array Registered notification events */
	protected array $events = [];

	/** @var array Registered notification methods */
	protected array $methods = [];

	/**
	 * Constructor
	 *
	 * @param Queue         $queue       Queue
	 * @param \ElggSession  $session     Session service
	 * @param EventsService $elgg_events Events service
	 */
	public function __construct(
			protected Queue $queue,
			protected \ElggSession $session,
			protected EventsService $elgg_events
	) {
	}

	/**
	 * Register a notification event
	 *
	 * @param string $type    'object', 'user', 'group', 'site'
	 * @param string $subtype The subtype or name of the entity
	 * @param string $action  An event is usually described by the first string passed to elgg_trigger_event().
	 *                        Examples include 'create', 'update', and 'publish' (default: 'create').
	 * @param string $handler NotificationEventHandler classname
	 *
	 * @return void
	 * @throws InvalidArgumentException
	 * @see elgg_register_notification_event()
	 */
	public function registerEvent(string $type, string $subtype, string $action = 'create', string $handler = NotificationEventHandler::class): void {
		if (!is_a($handler, NotificationEventHandler::class, true)) {
			throw new InvalidArgumentException('$handler needs to be a ' . NotificationEventHandler::class . ' classname');
		}
		
		if (!isset($this->events[$type])) {
			$this->events[$type] = [];
		}
		
		if (!isset($this->events[$type][$subtype])) {
			$this->events[$type][$subtype] = [];
		}
		
		if (!isset($this->events[$type][$subtype][$action])) {
			$this->events[$type][$subtype][$action] = [];
		}
		
		if (in_array($handler, $this->events[$type][$subtype][$action])) {
			return;
		}
		
		$this->events[$type][$subtype][$action][] = $handler;
	}

	/**
	 * Unregister a notification event
	 *
	 * @param string $type    'object', 'user', 'group', 'site'
	 * @param string $subtype The subtype of the entity
	 * @param string $action  The notification action to unregister (default: 'create')
	 * @param string $handler NotificationEventHandler class to unregister
	 *
	 * @return void
	 * @see elgg_unregister_notification_event()
	 */
	public function unregisterEvent(string $type, string $subtype, string $action = 'create', string $handler = NotificationEventHandler::class): void {
		if (!isset($this->events[$type][$subtype][$action])) {
			return;
		}
		
		$key = array_search($handler, $this->events[$type][$subtype][$action]);
		if ($key !== false) {
			unset($this->events[$type][$subtype][$action][$key]);
		}
		
		if (empty($this->events[$type][$subtype][$action])) {
			unset($this->events[$type][$subtype][$action]);
		}
		
		if (empty($this->events[$type][$subtype])) {
			unset($this->events[$type][$subtype]);
		}
		
		if (empty($this->events[$type])) {
			unset($this->events[$type]);
		}
	}

	/**
	 * Return the notification events
	 *
	 * @return array
	 */
	public function getEvents(): array {
		return $this->events;
	}

	/**
	 * Register a delivery method for notifications
	 *
	 * @param string $name The notification method name
	 *
	 * @return void
	 * @see elgg_register_notification_method()
	 */
	public function registerMethod(string $name): void {
		$this->methods[$name] = $name;
	}

	/**
	 * Unregister a delivery method for notifications
	 *
	 * @param string $name The notification method name
	 *
	 * @return void
	 * @see elgg_unregister_notification_method()
	 */
	public function unregisterMethod(string $name): void {
		if (!$this->isRegisteredMethod($name)) {
			return;
		}
		
		unset($this->methods[$name]);
	}

	/**
	 * Returns registered delivery methods for notifications
	 *
	 * @return string[]
	 * @see elgg_get_notification_methods()
	 */
	public function getMethods(): array {
		return $this->methods;
	}
	
	/**
	 * Check if a notification method is registed
	 *
	 * @param string $method the notification method
	 *
	 * @return bool
	 */
	public function isRegisteredMethod(string $method): bool {
		return in_array($method, $this->methods);
	}

	/**
	 * Add a notification event to the queue
	 *
	 * @param string           $action Action name
	 * @param \ElggData        $object The object of the action
	 * @param null|\ElggEntity $actor  (optional) The actor of the notification (default: logged-in user or owner of $object)
	 *
	 * @return void
	 */
	public function enqueueEvent(string $action, \ElggData $object, ?\ElggEntity $actor = null): void {
		$object_type = $object->getType();
		$object_subtype = $object->getSubtype();
		$actor = $actor ?? elgg_get_logged_in_user_entity(); // default to logged in user
		if (!isset($actor) && ($object instanceof \ElggEntity || $object instanceof \ElggExtender)) {
			// still not set, default to the owner of $object
			$actor = $object->getOwnerEntity() ?: null;
		}
		
		$handlers = $this->getSubscriptionHandlers($object_type, $object_subtype, $action);
		if (empty($handlers)) {
			return;
		}
		
		$params = [
			'action' => $action,
			'object' => $object,
			'actor' => $actor,
		];
		$registered = (bool) $this->elgg_events->triggerResults('enqueue', 'notification', $params, true);
		if (!$registered) {
			return;
		}
		
		$this->elgg_events->trigger('enqueue', 'notifications', $object);
		$this->queue->enqueue(new SubscriptionNotificationEvent($object, $action, $actor));
	}
	
	/**
	 * Get the subscription notification handlers
	 *
	 * @param string $type    'object', 'user', 'group', 'site'
	 * @param string $subtype The subtype of the entity
	 * @param string $action  The notification action
	 *
	 * @return array
	 */
	protected function getSubscriptionHandlers(string $type, string $subtype, string $action): array {
		if (!isset($this->events[$type][$subtype][$action])) {
			return [];
		}
		
		$result = [];
		foreach ($this->events[$type][$subtype][$action] as $handler) {
			if (is_a($handler, InstantNotificationEventHandler::class, true)) {
				continue;
			}
			
			$result[] = $handler;
		}
		
		return $result;
	}
	
	/**
	 * Get the instant notification handlers
	 *
	 * @param string $type    'object', 'user', 'group', 'site'
	 * @param string $subtype The subtype of the entity
	 * @param string $action  The notification action
	 *
	 * @return array
	 */
	protected function getInstantHandlers(string $type, string $subtype, string $action): array {
		if (!isset($this->events[$type][$subtype][$action])) {
			return [
				InstantNotificationEventHandler::class,
			];
		}
		
		$result = [];
		foreach ($this->events[$type][$subtype][$action] as $handler) {
			if (!is_a($handler, InstantNotificationEventHandler::class, true)) {
				continue;
			}
			
			$result[] = $handler;
		}
		
		if (empty($result)) {
			return [
				InstantNotificationEventHandler::class,
			];
		}
		
		return $result;
	}

	/**
	 * Pull notification events from queue until stop time is reached
	 *
	 * @param int $stopTime The Unix time to stop sending notifications
	 *
	 * @return int The number of notification events handled
	 */
	public function processQueue(int $stopTime): int {
		return elgg_call(ELGG_IGNORE_ACCESS, function() use ($stopTime) {
			$count = 0;
			
			while (time() < $stopTime) {
				// dequeue notification event
				$event = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function () {
					// showing disabled entities for deserialization
					return $this->queue->dequeue();
				});
				
				if (!$event instanceof NotificationEvent) {
					// queue is empty
					break;
				}
				
				$object = $event->getObject();
				if (!$object instanceof \ElggData || !$event->getActor()) {
					// event object or actor have been deleted since the event was enqueued
					continue;
				}
				
				$this->elgg_events->trigger('dequeue', 'notifications', $object);
				
				$handlers = $this->getSubscriptionHandlers($object->getType(), $object->getSubtype(), $event->getAction());
				if (empty($handlers)) {
					continue;
				}
				
				foreach ($handlers as $handler_class) {
					$handler = new $handler_class($event, $this);
					
					try {
						$handler->send();
						$count++;
					} catch (\Throwable $t) {
						$this->getLogger()->error($t);
					}
				}
			}
			
			return $count;
		});
	}

	/**
	 * Notify a user via their preferences.
	 *
	 * Returns an array in the form:
	 * <code>
	 * [
	 *    25 => [
	 *      'email' => true,
	 *      'sms' => false,
	 *    ],
	 *    55 => [],
	 * ]
	 * </code>
	 *
	 * @param \ElggEntity $sender     Sender of the notification
	 * @param \ElggUser[] $recipients An array of entities to notify
	 * @param array       $params     Notification parameters
	 *
	 * @uses $params['subject']          string
	 *                                   Default message subject
	 * @uses $params['body']             string
	 *                                   Default message body
	 * @uses $params['object']           null|\ElggEntity|\ElggAnnotation
	 *                                   The object that is triggering the notification.
	 * @uses $params['action']           null|string
	 *                                   Word that describes the action that is triggering the notification
	 *                                  (e.g. "create" or "update"). Defaults to "notify_user"
	 * @uses $params['summary']          null|string
	 *                                   Summary that notification plugins can use alongside the notification title and body.
	 * @uses $params['methods_override'] string|array
	 *                                   A string, or an array of strings specifying the delivery
	 *                                   methods to use - or leave blank for delivery using the
	 *                                   user's chosen delivery methods.
	 *
	 * @return array
	 */
	public function sendInstantNotifications(\ElggEntity $sender, array $recipients = [], array $params = []): array {
		if (empty($this->methods)) {
			return [];
		}
		
		$object = elgg_extract('object', $params);
		$action = elgg_extract('action', $params);
		
		$event = new InstantNotificationEvent($object, $action, $sender);
		
		$handler = new InstantNotificationEventHandler($event, $this, $params);
		$handler->setRecipients($recipients);
		
		return $handler->send();
	}
	
	/**
	 * Send an instant notification to a user
	 *
	 * @param \ElggUser        $recipient The recipient user
	 * @param string           $action    The action on $subject
	 * @param \ElggData        $subject   The notification subject
	 * @param array            $params    Additional params
	 *                                    use $params['methods_override'] to override the recipient notification methods (eg 'email' or 'site')
	 * @param null|\ElggEntity $from      Sender of the message
	 *
	 * @return array
	 * @since 6.3
	 */
	public function sendInstantNotification(\ElggUser $recipient, string $action, \ElggData $subject, array $params = [], ?\ElggEntity $from = null): array {
		if (empty($this->methods)) {
			return [];
		}
		
		$handlers = $this->getInstantHandlers($subject->getType(), $subject->getSubtype(), $action);
		if (empty($handlers)) {
			return [];
		}
		
		$from = $from ?? elgg_get_site_entity();
		
		$event = new InstantNotificationEvent($subject, $action, $from);
		
		$result = [];
		
		foreach ($handlers as $handler_class) {
			$handler = new $handler_class($event, $this, $params);
			$handler->setRecipients([$recipient]);
			
			try {
				$handler_result = $handler->send();
				
				$result = $result + $handler_result;
			} catch (\Throwable $t) {
				$this->getLogger()->error($t);
			}
		}
		
		return $result;
	}
}
