<?php

namespace Elgg\Notifications;

use Psr\Log\LogLevel;

/**
 * Notification Event Handler handles preparation of a notification
 *
 * @since 4.0
 */
class NotificationEventHandler {

	/** @var NotificationEvent */
	protected $event;

	/** @var NotificationsService */
	protected $service;

	/** @var array */
	protected $subscriptions = [];

	/** @var array */
	protected $params = [];

	/**
	 * Constructor
	 *
	 * @param NotificationEvent    $event   event to handle
	 * @param NotificationsService $service service that handles the events
	 * @param array                $params  additional params for event handling
	 */
	public function __construct(
			NotificationEvent $event,
			NotificationsService $service,
			array $params = []
	) {
		$this->event = $event;
		$this->service = $service;
		$this->params = $params;
	}
	
	/**
	 * Process the event
	 *
	 * @return array delivery information
	 */
	final public function send(): array {
		$deliveries = [];
		
		$params = $this->params;
		$params['handler'] = $this;
		$params['event'] = $this->event;
		$params['subscriptions'] = $this->prepareSubscriptions();

		// return false to stop the default notification sender
		if (_elgg_services()->hooks->trigger('send:before', 'notifications', $params, true)) {
			$deliveries = $this->sendNotifications($params['subscriptions'], $params);
		}
		
		$params['deliveries'] = $deliveries;
		_elgg_services()->hooks->trigger('send:after', 'notifications', $params);
		
		return $deliveries;
	}
	
	/**
	 * Returns subscriptions
	 *
	 * @return array
	 */
	final protected function prepareSubscriptions(): array {
		$subscriptions = $this->getSubscriptions();
		
		$params = [
			'event' => $this->event,
			'methods' => $this->getMethods(),
			'methods_override' => (array) elgg_extract('methods_override', $this->params, []),
		];
		$subscriptions = _elgg_services()->hooks->trigger('get', 'subscriptions', $params, $subscriptions);
		
		return _elgg_services()->subscriptions->filterSubscriptions($subscriptions, $this->event);
	}
	
	/**
	 * Returns subscriptions for the event
	 *
	 * @return array
	 */
	public function getSubscriptions(): array {
		return _elgg_services()->subscriptions->getNotificationEventSubscriptions($this->event, $this->getMethods(), $this->getNotificationSubsciptionExclusionGUIDs());
	}
	
	/**
	 * Get an array of GUIDs to not get the subscription records for
	 *
	 * @return int[]
	 */
	final protected function getNotificationSubsciptionExclusionGUIDs(): array {
		$object = $this->event->getObject();
		if (!$object instanceof \ElggEntity) {
			return [];
		}
		
		$exclude = [];
		if ($this->excludeOwnerSubscribers()) {
			$exclude[] = $object->owner_guid;
		}
		
		if ($this->excludeContainerSubscribers()) {
			$exclude[] = $object->container_guid;
		}
		
		if ($this->excludeEntitySubscribers()) {
			$exclude[] = $object->guid;
		}
		
		return $exclude;
	}
	
	/**
	 * Exclude the NotificationEvent object owner_guid when fetching the subscription records for this notification
	 *
	 * @return bool
	 * @see NotificationEventHandler::getSubscriptions();
	 */
	protected function excludeOwnerSubscribers(): bool {
		return false;
	}
	
	/**
	 * Exclude the NotificationEvent object container_guid when fetching the subscription records for this notification
	 *
	 * @return bool
	 * @see NotificationEventHandler::getSubscriptions();
	 */
	protected function excludeContainerSubscribers(): bool {
		return false;
	}
	
	/**
	 * Exclude the NotificationEvent object guid when fetching the subscription records for this notification
	 *
	 * @return bool
	 * @see NotificationEventHandler::getSubscriptions();
	 */
	protected function excludeEntitySubscribers(): bool {
		return false;
	}
	
	/**
	 * Returns methods to be used for this notification
	 *
	 * @return array
	 */
	final public function getMethods(): array {
		return $this->service->getMethods();
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
	 * @param array $subscriptions Subscriptions for this event
	 * @param array $params        Default notification parameters
	 *
	 * @return array
	 */
	final protected function sendNotifications($subscriptions, array $params = []) {
		if (empty($this->getMethods())) {
			return [];
		}

		$result = [];
		foreach ($subscriptions as $guid => $methods) {
			foreach ($methods as $method) {
				$result[$guid][$method] = false;
				
				if ($this->service->isRegisteredMethod($method)) {
					$result[$guid][$method] = $this->sendNotification($guid, $method, $params);
				}
			}
		}

		_elgg_services()->logger->info("Results for the notification event {$this->event->getDescription()}: " . print_r($result, true));
		return $result;
	}
	
	/**
	 * Send a notification to a subscriber
	 *
	 * @param int    $guid   The guid of the subscriber
	 * @param string $method The notification method
	 * @param array  $params Default notification params
	 *
	 * @return bool
	 */
	final protected function sendNotification(int $guid, string $method, array $params = []): bool {
		if (!_elgg_services()->hooks->hasHandler('send', "notification:{$method}")) {
			// no way to deliver given the current method, so quitting early
			return false;
		}

		$actor = $this->event->getActor();
		$object = $this->event->getObject();

		if ($this->event instanceof InstantNotificationEvent) {
			$recipient = _elgg_services()->entityTable->get($guid);
			/* @var \ElggEntity $recipient */
			$subject = elgg_extract('subject', $params, '');
			$body = elgg_extract('body', $params, '');
			$summary = elgg_extract('summary', $params, '');
		} else {
			$recipient = _elgg_services()->entityTable->get($guid, 'user');
			if (!$recipient instanceof \ElggUser || $recipient->isBanned()) {
				return false;
			}
		
			if (!$actor || !$object) {
				return false;
			}

			if ($object instanceof \ElggEntity && !_elgg_services()->accessCollections->hasAccessToEntity($object, $recipient)) {
				// Recipient does not have access to the notification object
				// The access level may have changed since the event was enqueued
				return false;
			}

			$subject = $this->getNotificationSubject($recipient, $method);
			$body = $this->getNotificationBody($recipient, $method);
			$summary = $this->getNotificationSummary($recipient, $method);
			
			if (!isset($params['url'])) {
				$params['url'] = $this->getNotificationURL($recipient, $method) ?: null;
			}
		}
		
		$params['subject'] = $subject;
		$params['body'] = $body;
		$params['summary'] = $summary;
		$params['event'] = $this->event;
		$params['method'] = $method;
		$params['sender'] = $actor;
		$params['recipient'] = $recipient;
		$params['language'] = $recipient->getLanguage();
		$params['object'] = $object;
		$params['action'] = $this->event->getAction();
		$params['add_salutation'] = elgg_extract('add_salutation', $params, true);

		$notification = $this->prepareNotification($params);
		return $this->deliverNotification($notification, $method);
	}
	
	/**
	 * Deliver a notification
	 *
	 * @param Notification $notification Notification to deliver
	 * @param string       $method       Method to use for delivery
	 *
	 * @return bool
	 */
	final protected function deliverNotification(Notification $notification, string $method): bool {
		// return true to indicate the notification has been sent
		$params = [
			'notification' => $notification,
			'event' => $this->event,
		];

		$result = _elgg_services()->hooks->trigger('send', "notification:{$method}", $params, false);
		
		if (_elgg_services()->logger->isLoggable(LogLevel::INFO)) {
			$logger_data = print_r((array) $notification->toObject(), true);
			if ($result) {
				_elgg_services()->logger->info('Notification sent: ' . $logger_data);
			} else {
				_elgg_services()->logger->info('Notification was not sent: ' . $logger_data);
			}
		}
		
		return $result;
	}
	
	/**
	 * Prepares a notification for delivery
	 *
	 * @param array $params Parameters to initialize notification with
	 *
	 * @throws \RuntimeException
	 *
	 * @return Notification
	 */
	final protected function prepareNotification(array $params): Notification {
		$notification = new Notification($params['sender'], $params['recipient'], $params['language'], $params['subject'], $params['body'], $params['summary'], $params);

		$notification = _elgg_services()->hooks->trigger('prepare', 'notification', $params, $notification);
		if (!$notification instanceof Notification) {
			throw new \RuntimeException("'prepare','notification' hook must return an instance of " . Notification::class);
		}

		$type = 'notification:' . $this->event->getDescription();
		$notification = _elgg_services()->hooks->trigger('prepare', $type, $params, $notification);
		if (!$notification instanceof Notification) {
			throw new \RuntimeException("'prepare','{$type}' hook must return an instance of " . Notification::class);
		}

		if (elgg_extract('add_salutation', $notification->params) === true) {
			$viewtype = elgg_view_exists('notifications/body') ? '' : 'default';
			$notification->body = _elgg_view_under_viewtype('notifications/body', ['notification' => $notification], $viewtype);
		}
		
		$notification = _elgg_services()->hooks->trigger('format', "notification:{$params['method']}", [], $notification);
		if (!$notification instanceof Notification) {
			throw new \RuntimeException("'format','notification:{$params['method']}' hook must return an instance of " . Notification::class);
		}
		
		return $notification;
	}

	/**
	 * Get subject for the notification
	 *
	 * Plugins can define a subtype specific subject simply by providing a
	 * translation for the string "notification:<action>:<type>:<subtype>:subject".
	 *
	 * @param \ElggUser $recipient Notification recipient
	 * @param string    $method    Method
	 *
	 * @return string Notification subject in the recipient's language
	 */
	protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		$actor = $this->event->getActor();
		$object = $this->event->getObject();
		
		$language = $recipient->getLanguage();

		// Check custom notification subject for the action/type/subtype combination
		$subject_key = "notification:{$this->event->getDescription()}:subject";
		if (_elgg_services()->translator->languageKeyExists($subject_key, $language)) {
			$display_name = '';
			if ($object instanceof \ElggEntity) {
				$display_name = $object->getDisplayName();
			}
			
			return _elgg_services()->translator->translate($subject_key, [
				$actor->getDisplayName(),
				$display_name,
			], $language);
		}

		// Fall back to default subject
		return _elgg_services()->translator->translate('notification:subject', [$actor->getDisplayName()], $language);
	}

	/**
	 * Get body for the notification
	 *
	 * Plugin can define a subtype specific body simply by providing a
	 * translation for the string "notification:<action>:<type>:<subtype>:body".
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
	 * @param \ElggUser $recipient Notification recipient
	 * @param string    $method    Method
	 *
	 * @return string Notification body in the recipient's language
	 */
	protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		$actor = $this->event->getActor();
		$object = $this->event->getObject();
		/* @var \ElggObject $object */
		$language = $recipient->getLanguage();

		// Check custom notification body for the action/type/subtype combination
		$body_key = "notification:{$this->event->getDescription()}:body";
		if (_elgg_services()->translator->languageKeyExists($body_key, $language)) {
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

			return _elgg_services()->translator->translate($body_key, [
				$actor->getDisplayName(),
				$display_name,
				$container_name,
				$object->description,
				$object->getURL(),
			], $language);
		}

		// Fall back to default body
		return _elgg_services()->translator->translate('notification:body', [$object->getURL()], $language);
	}
	
	/**
	 * Return the summary for a notification
	 *
	 * @param \ElggUser $recipient Notification recipient
	 * @param string    $method    Method
	 *
	 * @return string
	 */
	protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		return '';
	}
	
	/**
	 * Returns the url related to this notification
	 *
	 * @param \ElggUser $recipient Notification recipient
	 * @param string    $method    Method
	 *
	 * @return string
	 */
	protected function getNotificationURL(\ElggUser $recipient, string $method): string {
		$object = $this->event->getObject();
		
		return $object instanceof \ElggEntity ? $object->getURL() : '';
	}
	
	/**
	 * Is this event configurable by the user on the notification settings page
	 *
	 * @return bool
	 */
	public static function isConfigurableByUser(): bool {
		return true;
	}
}
