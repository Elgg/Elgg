<?php

namespace Elgg\Notifications;

use Elgg\EventsService;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Traits\Loggable;
use Elgg\Queue\Queue;

/**
 * Notifications service
 *
 * @internal
 * @since 1.9.0
 */
class NotificationsService {

	use Loggable;
	
	/** @var Queue */
	protected $queue;

	/** @var EventsService */
	protected $elgg_events;

	/** @var \ElggSession */
	protected $session;
	
	/** @var array Registered notification events */
	protected $events = [];

	/** @var array Registered notification methods */
	protected $methods = [];

	/**
	 * Constructor
	 *
	 * @param Queue         $queue       Queue
	 * @param \ElggSession  $session     Session service
	 * @param EventsService $elgg_events Events service
	 */
	public function __construct(
			Queue $queue,
			\ElggSession $session,
			EventsService $elgg_events
	) {

		$this->queue = $queue;
		$this->session = $session;
		$this->elgg_events = $elgg_events;
	}

	/**
	 * Register a notification event
	 *
	 * @param string $type    'object', 'user', 'group', 'site'
	 * @param string $subtype The subtype or name of the entity
	 * @param array  $actions Array of actions or empty array for the action event.
	 *                        An event is usually described by the first string passed
	 *                        to elgg_trigger_event(). Examples include
	 *                        'create', 'update', and 'publish'. The default is 'create'.
	 * @param string $handler NotificationEventHandler classname
	 *
	 * @return void
	 * @throws InvalidArgumentException
	 * @see elgg_register_notification_event()
	 */
	public function registerEvent(string $type, string $subtype, array $actions = [], string $handler = NotificationEventHandler::class) {
		if (!is_a($handler, NotificationEventHandler::class, true)) {
			throw new InvalidArgumentException('$handler needs to be a ' . NotificationEventHandler::class . ' classname');
		}
		
		if (!isset($this->events[$type])) {
			$this->events[$type] = [];
		}
		
		if (!isset($this->events[$type][$subtype])) {
			$this->events[$type][$subtype] = [];
		}
		
		if (empty($actions) && !array_key_exists('create', $this->events[$type][$subtype])) {
			$actions[] = 'create';
		}
		
		foreach ($actions as $action) {
			$this->events[$type][$subtype][$action] = $handler;
		}
	}

	/**
	 * Unregister a notification event
	 *
	 * @param string $type    'object', 'user', 'group', 'site'
	 * @param string $subtype The subtype of the entity
	 * @param array  $actions The notification action to unregister, leave empty for all actions
	 *
	 * @return void
	 * @see elgg_unregister_notification_event()
	 */
	public function unregisterEvent(string $type, string $subtype, array $actions = []): void {

		if (empty($actions)) {
			unset($this->events[$type][$subtype]);
		}
		
		foreach ($actions as $action) {
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
	 * Check if a notification event is registered
	 *
	 * @param string $type    'object', 'user', 'group', 'site'
	 * @param string $subtype The subtype of the entity
	 * @param string $action  The notification action to check
	 *
	 * @return bool
	 * @since 5.0
	 */
	public function isRegisteredEvent(string $type, string $subtype, string $action): bool {
		return isset($this->events[$type][$subtype][$action]);
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
	 * @param string      $action Action name
	 * @param \ElggData   $object The object of the action
	 * @param \ElggEntity $actor  (optional) The actor of the notification (default: logged in user or owner of $object)
	 *
	 * @return void
	 */
	public function enqueueEvent(string $action, \ElggData $object, \ElggEntity $actor = null): void {
		$object_type = $object->getType();
		$object_subtype = $object->getSubtype();
		$actor = $actor ?? elgg_get_logged_in_user_entity(); // default to logged in user
		if (!isset($actor) && ($object instanceof \ElggEntity || $object instanceof \ElggExtender)) {
			// still not set, default to the owner of $object
			$actor = $object->getOwnerEntity() ?: null;
		}
		
		$registered = $this->isRegisteredEvent($object_type, $object_subtype, $action);
		if ($registered) {
			$params = [
				'action' => $action,
				'object' => $object,
				'actor' => $actor,
			];
			$registered = (bool) $this->elgg_events->triggerResults('enqueue', 'notification', $params, $registered);
		}
		
		if (!$registered) {
			return;
		}
		
		$this->elgg_events->trigger('enqueue', 'notifications', $object);
		$this->queue->enqueue(new SubscriptionNotificationEvent($object, $action, $actor));
	}
	
	/**
	 * Returns notification event handler based on event
	 *
	 * @param NotificationEvent $event event to get event handler for
	 *
	 * @return NotificationEventHandler
	 */
	protected function getNotificationHandler(NotificationEvent $event): NotificationEventHandler {
		$object = $event->getObject();
		$handler = NotificationEventHandler::class;
		
		if (isset($this->events[$object->getType()][$object->getSubtype()][$event->getAction()])) {
			$handler = $this->events[$object->getType()][$object->getSubtype()][$event->getAction()];
		}
		
		return new $handler($event, $this);
	}

	/**
	 * Pull notification events from queue until stop time is reached
	 *
	 * @param int  $stopTime The Unix time to stop sending notifications
	 * @param bool $matrix   If true, will return delivery matrix instead of a notifications event count
	 *
	 * @return int|array The number of notification events handled, or a delivery matrix
	 */
	public function processQueue($stopTime, $matrix = false) {
		
		return elgg_call(ELGG_IGNORE_ACCESS, function() use ($stopTime, $matrix) {
			$delivery_matrix = [];
			
			$count = 0;
			
			while (time() < $stopTime) {
				// dequeue notification event
				$event = $this->queue->dequeue();
				/* @var $event NotificationEvent */
				
				if (!$event) {
					// queue is empty
					break;
				}
				
				if (!$event instanceof NotificationEvent || !$event->getObject() || !$event->getActor()) {
					// event object or actor have been deleted since the event was enqueued
					continue;
				}
				
				$this->elgg_events->trigger('dequeue', 'notifications', $event->getObject());
				
				$handler = $this->getNotificationHandler($event);
				
				try {
					$delivery_matrix[$event->getDescription()] = $handler->send();
					$count++;
				} catch (\Throwable $t) {
					$this->getLogger()->error($t);
				}
			}
			
			return $matrix ? $delivery_matrix : $count;
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
	public function sendInstantNotifications(\ElggEntity $sender, array $recipients = [], array $params = []) {
		if (empty($this->methods)) {
			return [];
		}
		
		$params['recipients'] = array_filter($recipients, function($e) {
			return ($e instanceof \ElggUser);
		});
		
		$object = elgg_extract('object', $params);
		$action = elgg_extract('action', $params);
		
		$event = new InstantNotificationEvent($object, $action, $sender);
		
		$handler = new InstantNotificationEventHandler($event, $this, $params);
		
		return $handler->send();
	}
}
