<?php

namespace Elgg\Notifications;

use Elgg\Database\EntityTable;
use Elgg\I18n\Translator;
use Elgg\Loggable;
use Elgg\Logger;
use Elgg\PluginHooksService;
use Elgg\Queue\Queue;
use ElggData;
use ElggEntity;
use ElggSession;
use ElggUser;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use RuntimeException;

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

	use Loggable;

	const QUEUE_NAME = 'notifications';

	/** @var SubscriptionsService */
	protected $subscriptions;

	/** @var Queue */
	protected $queue;

	/** @var PluginHooksService */
	protected $hooks;

	/** @var ElggSession */
	protected $session;

	/** @var Translator */
	protected $translator;

	/** @var EntityTable */
	protected $entities;
	
	/** @var array Registered notification events */
	protected $events = [];

	/** @var array Registered notification methods */
	protected $methods = [];

	/** @var array Deprecated notification handlers */
	protected $deprHandlers = [];

	/** @var array Deprecated message subjects */
	protected $deprSubjects = [];

	/**
	 * Constructor
	 *
	 * @param SubscriptionsService $subscriptions Subscription service
	 * @param Queue                $queue         Queue
	 * @param PluginHooksService   $hooks         Plugin hook service
	 * @param ElggSession          $session       Session service
	 * @param Translator           $translator    Translator
	 * @param EntityTable          $entities      Entity table
	 * @param LoggerInterface      $logger        Logger
	 */
	public function __construct(
			SubscriptionsService $subscriptions,
			Queue $queue, PluginHooksService $hooks,
			ElggSession $session,
			Translator $translator,
			EntityTable $entities,
			LoggerInterface $logger
	) {

		$this->subscriptions = $subscriptions;
		$this->queue = $queue;
		$this->hooks = $hooks;
		$this->session = $session;
		$this->translator = $translator;
		$this->entities = $entities;
		$this->logger = $logger;
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
	 * @return void
	 *
	 * @see elgg_register_notification_event()
	 * @access private
	 */
	public function registerEvent($type, $subtype, array $actions = []) {

		if (!isset($this->events[$type])) {
			$this->events[$type] = [];
		}
		if (!isset($this->events[$type][$subtype])) {
			$this->events[$type][$subtype] = [];
		}

		$action_list =& $this->events[$type][$subtype];
		if ($actions) {
			$action_list = array_unique(array_merge($action_list, $actions));
		} elseif (!in_array('create', $action_list)) {
			$action_list[] = 'create';
		}
	}

	/**
	 * Unregister a notification event
	 *
	 * @param string $type    'object', 'user', 'group', 'site'
	 * @param string $subtype The type of the entity
	 *
	 * @return bool
	 *
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
	 * Return the notification events
	 *
	 * @return array
	 *
	 * @access private
	 */
	public function getEvents() {
		return $this->events;
	}

	/**
	 * Register a delivery method for notifications
	 *
	 * @param string $name The notification method name
	 * @return void
	 *
	 * @see elgg_register_notification_method()
	 * @access private
	 */
	public function registerMethod($name) {
		$this->methods[$name] = $name;
	}

	/**
	 * Unregister a delivery method for notifications
	 *
	 * @param string $name The notification method name
	 * @return bool
	 *
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
	 * Returns registered delivery methods for notifications
	 *
	 * @return string[]
	 *
	 * @see elgg_get_notification_methods()
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
			if (!empty($this->events[$object_type][$object_subtype]) && in_array($action, $this->events[$object_type][$object_subtype])) {
				$registered = true;
			}

			if ($registered) {
				$params = [
					'action' => $action,
					'object' => $object,
				];
				$registered = $this->hooks->trigger('enqueue', 'notification', $params, $registered);
			}

			if ($registered) {
				$this->queue->enqueue(new SubscriptionNotificationEvent($object, $action));
			}
		}
	}

	/**
	 * Pull notification events from queue until stop time is reached
	 *
	 * @param int  $stopTime The Unix time to stop sending notifications
	 * @param bool $matrix   If true, will return delivery matrix instead of a notifications event count
	 * @return int|array The number of notification events handled, or a delivery matrix
	 * @access private
	 */
	public function processQueue($stopTime, $matrix = false) {

		$this->subscriptions->methods = $this->methods;

		$delivery_matrix = [];

		$count = 0;

		// @todo grab mutex

		$ia = $this->session->setIgnoreAccess(true);

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

			$subscriptions = $this->subscriptions->getSubscriptions($event);
			
			// return false to stop the default notification sender
			$params = [
				'event' => $event,
				'subscriptions' => $subscriptions
			];
			
			$deliveries = [];
			if ($this->hooks->trigger('send:before', 'notifications', $params, true)) {
				$deliveries = $this->sendNotifications($event, $subscriptions);
			}
			$params['deliveries'] = $deliveries;
			$this->hooks->trigger('send:after', 'notifications', $params);
			$count++;

			$delivery_matrix[$event->getDescription()] = $deliveries;
		}

		// release mutex

		$this->session->setIgnoreAccess($ia);

		return $matrix ? $delivery_matrix : $count;
	}

	/**
	 * Sends the notifications based on subscriptions
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
	 * @param NotificationEvent $event         Notification event
	 * @param array             $subscriptions Subscriptions for this event
	 * @param array             $params        Default notification parameters
	 * @return array
	 * @access private
	 */
	protected function sendNotifications($event, $subscriptions, array $params = []) {

		if (!$this->methods) {
			return 0;
		}

		$result = [];
		foreach ($subscriptions as $guid => $methods) {
			foreach ($methods as $method) {
				$result[$guid][$method] = false;
				if (in_array($method, $this->methods)) {
					$result[$guid][$method] = $this->sendNotification($event, $guid, $method, $params);
				}
			}
		}

		$this->logger->info("Results for the notification event {$event->getDescription()}: " . print_r($result, true));
		return $result;
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
	 * @param ElggEntity $sender     Sender of the notification
	 * @param ElggUser[] $recipients An array of entities to notify
	 * @param array      $params     Notification parameters
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
	 * @access private
	 */
	public function sendInstantNotifications(\ElggEntity $sender, array $recipients = [], array $params = []) {

		if (!$sender instanceof \ElggEntity) {
			throw new InvalidArgumentException("Notification sender must be a valid entity");
		}
		
		$deliveries = [];

		if (!$this->methods) {
			return $deliveries;
		}
		
		$recipients = array_filter($recipients, function($e) {
			return ($e instanceof \ElggUser);
		});
		
		$object = elgg_extract('object', $params);
		$action = elgg_extract('action', $params);

		$methods_override = elgg_extract('methods_override', $params);
		unset($params['methods_override']);
		if ($methods_override && !is_array($methods_override)) {
			$methods_override = [$methods_override];
		}

		$event = new InstantNotificationEvent($object, $action, $sender);

		$params['event'] = $event;
		$params['origin'] = Notification::ORIGIN_INSTANT;

		$subscriptions = [];

		foreach ($recipients as $recipient) {
			// Are we overriding delivery?
			$methods = $methods_override;
			if (empty($methods)) {
				$methods = [];
				$user_settings = $recipient->getNotificationSettings();
				foreach ($user_settings as $method => $enabled) {
					if ($enabled) {
						$methods[] = $method;
					}
				}
			}

			$subscriptions[$recipient->guid] = $methods;
		}

		$hook_params = [
			'event' => $params['event'],
			'origin' => $params['origin'],
			'methods_override' => $methods_override,
		];
		$subscriptions = $this->hooks->trigger('get', 'subscriptions', $hook_params, $subscriptions);
		
		$params['subscriptions'] = $subscriptions;

		// return false to stop the default notification sender
		if ($this->hooks->trigger('send:before', 'notifications', $params, true)) {
			$deliveries = $this->sendNotifications($event, $subscriptions, $params);
		}
		$params['deliveries'] = $deliveries;
		$this->hooks->trigger('send:after', 'notifications', $params);

		return $deliveries;
	}

	/**
	 * Send a notification to a subscriber
	 *
	 * @param NotificationEvent $event  The notification event
	 * @param int               $guid   The guid of the subscriber
	 * @param string            $method The notification method
	 * @param array             $params Default notification params
	 * @return bool
	 * @access private
	 */
	protected function sendNotification(NotificationEvent $event, $guid, $method, array $params = []) {

		$actor = $event->getActor();
		$object = $event->getObject();

		if ($event instanceof InstantNotificationEvent) {
			$recipient = $this->entities->get($guid);
			/* @var \ElggEntity $recipient */
			$subject = elgg_extract('subject', $params, '');
			$body = elgg_extract('body', $params, '');
			$summary = elgg_extract('summary', $params, '');
		} else {
			$recipient = $this->entities->get($guid, 'user');
			/* @var \ElggUser $recipient */
			if (!$recipient || $recipient->isBanned()) {
				return false;
			}
		
			if ($recipient->getGUID() == $event->getActorGUID()) {
				// Content creators should not be receiving subscription
				// notifications about their own content
				return false;
			}
			
			if (!$actor || !$object) {
				return false;
			}

			if ($object instanceof ElggEntity && !has_access_to_entity($object, $recipient)) {
				// Recipient does not have access to the notification object
				// The access level may have changed since the event was enqueued
				return false;
			}

			$subject = $this->getNotificationSubject($event, $recipient);
			$body = $this->getNotificationBody($event, $recipient);
			$summary = '';
			
			$params['origin'] = Notification::ORIGIN_SUBSCRIPTIONS;
		}

		$language = $recipient->language;
		$params['event'] = $event;
		$params['method'] = $method;
		$params['sender'] = $actor;
		$params['recipient'] = $recipient;
		$params['language'] = $language;
		$params['object'] = $object;
		$params['action'] = $event->getAction();

		$notification = new Notification($actor, $recipient, $language, $subject, $body, $summary, $params);

		$notification = $this->hooks->trigger('prepare', 'notification', $params, $notification);
		if (!$notification instanceof Notification) {
			throw new RuntimeException("'prepare','notification' hook must return an instance of " . Notification::class);
		}

		$type = 'notification:' . $event->getDescription();
		if ($this->hooks->hasHandler('prepare', $type)) {
			$notification = $this->hooks->trigger('prepare', $type, $params, $notification);
			if (!$notification instanceof Notification) {
				throw new RuntimeException("'prepare','$type' hook must return an instance of " . Notification::class);
			}
		} else {
			// pre Elgg 1.9 notification message generation
			$notification = $this->getDeprecatedNotificationBody($notification, $event, $method);
		}

		$notification = $this->hooks->trigger('format', "notification:$method", [], $notification);
		if (!$notification instanceof Notification) {
			throw new RuntimeException("'format','notification:$method' hook must return an instance of " . Notification::class);
		}

		if ($this->hooks->hasHandler('send', "notification:$method")) {
			// return true to indicate the notification has been sent
			$params = [
				'notification' => $notification,
				'event' => $event,
			];

			$result = $this->hooks->trigger('send', "notification:$method", $params, false);
			if ($this->logger->isLoggable(LogLevel::INFO)) {
				$logger_data = print_r((array) $notification->toObject(), true);
				if ($result) {
					$this->logger->info("Notification sent: " . $logger_data);
				} else {
					$this->logger->info("Notification was not sent: " . $logger_data);
				}
			}
			return $result;
		} else {
			// pre Elgg 1.9 notification handler
			$userGuid = $notification->getRecipientGUID();
			$senderGuid = $notification->getSenderGUID();
			$subject = $notification->subject;
			$body = $notification->body;
			$params = $notification->params;
			return (bool) _elgg_notify_user($userGuid, $senderGuid, $subject, $body, $params, [$method]);
		}
	}

	/**
	 * Get subject for the notification
	 *
	 * Plugins can define a subtype specific subject simply by providing a
	 * translation for the string "notification:subject:<action>:<type>:<subtype".
	 *
	 * For example in mod/blog/languages/en.php:
	 *
	 *     'notification:subject:publish:object:blog' => '%s published a blog called %s'
	 *
	 * @param NotificationEvent $event     Notification event
	 * @param ElggUser          $recipient Notification recipient
	 * @return string Notification subject in the recipient's language
	 */
	private function getNotificationSubject(NotificationEvent $event, ElggUser $recipient) {
		$actor = $event->getActor();
		$object = $event->getObject();
		/* @var \ElggObject $object */
		$language = $recipient->language;

		// Check custom notification subject for the action/type/subtype combination
		$subject_key = "notification:{$event->getDescription()}:subject";
		if ($this->translator->languageKeyExists($subject_key, $language)) {
			if ($object instanceof \ElggEntity) {
				$display_name = $object->getDisplayName();
			} else {
				$display_name = '';
			}
			return $this->translator->translate($subject_key, [
				$actor->getDisplayName(),
				$display_name,
			], $language);
		}

		// Fall back to default subject
		return $this->translator->translate('notification:subject', [$actor->getDisplayName()], $language);
	}

	/**
	 * Get body for the notification
	 *
	 * Plugin can define a subtype specific body simply by providing a
	 * translation for the string "notification:body:<action>:<type>:<subtype".
	 *
	 * For example in mod/blog/languages/en.php:
	 *
	 *    'notification:body:publish:object:blog' => '
	 *         Hi %s!
	 *
	 *         %s has created a new post called "%s" in the group %s.
	 *
	 *         It says:
	 *
	 *         "%s"
	 *
	 *         You can comment the post here:
	 *         %s
	 *     ',
	 *
	 * The arguments passed into the translation are:
	 *     1. Recipient's name
	 *     2. Name of the user who triggered the notification
	 *     3. Title of the content
	 *     4. Name of the content's container
	 *     5. The actual content (entity's 'description' field)
	 *     6. URL to the content
	 *
	 * Argument swapping can be used to change the order of the parameters.
	 * See http://php.net/manual/en/function.sprintf.php#example-5427
	 *
	 * @param NotificationEvent $event     Notification event
	 * @param ElggUser          $recipient Notification recipient
	 * @return string Notification body in the recipient's language
	 */
	private function getNotificationBody(NotificationEvent $event, ElggUser $recipient) {
		$actor = $event->getActor();
		$object = $event->getObject();
		/* @var \ElggObject $object */
		$language = $recipient->language;

		// Check custom notification body for the action/type/subtype combination
		$body_key = "notification:{$event->getDescription()}:body";
		if ($this->translator->languageKeyExists($body_key, $language)) {
			if ($object instanceof \ElggEntity) {
				$display_name = $object->getDisplayName();
				$container_name = '';
				$container = $object->getContainerEntity();
				if ($container) {
					$container_name = $container->getDisplayName();
				}
			} else {
				$display_name = '';
				$container_name = '';
			}

			return $this->translator->translate($body_key, [
				$recipient->getDisplayName(),
				$actor->getDisplayName(),
				$display_name,
				$container_name,
				$object->description,
				$object->getURL(),
			], $language);
		}

		// Fall back to default body
		return $this->translator->translate('notification:body', [$object->getURL()], $language);
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
		$data = [];
		foreach ($this->methods as $method) {
			$data[$method] = 'empty';
		}
		return $data;
	}

	/**
	 * Get the notification body using a pre-Elgg 1.9 plugin hook
	 *
	 * @param Notification      $notification Notification
	 * @param NotificationEvent $event        Event
	 * @param string            $method       Method
	 * @return Notification
	 */
	protected function getDeprecatedNotificationBody(Notification $notification, NotificationEvent $event, $method) {
		$entity = $event->getObject();
		if (!$entity) {
			return $notification;
		}
		$params = [
			'entity' => $entity,
			'to_entity' => $notification->getRecipient(),
			'method' => $method,
		];
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
			$this->deprSubjects[$type] = [];
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
}
