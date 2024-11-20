<?php

namespace Elgg\Notifications;

use Elgg\Exceptions\RuntimeException;
use Elgg\Traits\Loggable;
use Psr\Log\LogLevel;

/**
 * Notification Event Handler handles preparation of a notification
 *
 * @since 4.0
 */
class NotificationEventHandler {

	use Loggable;
	
	/** @var NotificationEvent */
	protected $event;

	/** @var NotificationsService */
	protected $service;

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
		if (_elgg_services()->events->triggerResults('send:before', 'notifications', $params, true)) {
			$deliveries = $this->sendNotifications($params['subscriptions'], $params);
		}
		
		$params['deliveries'] = $deliveries;
		_elgg_services()->events->triggerResults('send:after', 'notifications', $params);
		
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
		$subscriptions = _elgg_services()->events->triggerResults('get', 'subscriptions', $params, $subscriptions);
		
		return _elgg_services()->subscriptions->filterSubscriptions($subscriptions, $this->event, $this->filterMutedSubscriptions());
	}
	
	/**
	 * Should muted subscribers be filtered
	 *
	 * @return bool
	 * @since 4.1
	 */
	protected function filterMutedSubscriptions(): bool {
		return (bool) elgg_extract('apply_muting', $this->params, true);
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
	final protected function sendNotifications(array $subscriptions, array $params = []): array {
		if (empty($this->getMethods())) {
			return [];
		}
		
		$translator = _elgg_services()->translator;
		$current_language = $translator->getCurrentLanguage();
		
		$result = [];
		foreach ($subscriptions as $guid => $methods) {
			$recipient = _elgg_services()->entityTable->get($guid);
			if ($recipient instanceof \ElggUser) {
				$translator->setCurrentLanguage($recipient->getLanguage());
			}
			
			try {
				foreach ($methods as $method) {
					$result[$guid][$method] = false;
					
					if ($this->service->isRegisteredMethod($method)) {
						$result[$guid][$method] = $this->sendNotification($guid, $method, $params);
					}
				}
			} catch (\Throwable $t) {
				$translator->setCurrentLanguage($current_language);
				throw $t;
			}
			
			$translator->setCurrentLanguage($current_language);
		}
		
		$this->getLogger()->info("Results for the notification event {$this->event->getDescription()}: " . print_r($result, true));
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
		if (!_elgg_services()->events->hasHandler('send', "notification:{$method}")) {
			// no way to deliver given the current method, so quitting early
			return false;
		}

		$actor = $this->event->getActor();
		$object = $this->event->getObject();

		if ($this->event instanceof InstantNotificationEvent) {
			/* @var \ElggEntity $recipient */
			$recipient = _elgg_services()->entityTable->get($guid);
			
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

			if ($object instanceof \ElggEntity && !$object->hasAccess($recipient->guid)) {
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
		$params['language'] = $recipient instanceof \ElggUser ? $recipient->getLanguage() : _elgg_services()->translator->getCurrentLanguage();
		$params['object'] = $object;
		$params['action'] = $this->event->getAction();
		$params['add_salutation'] = elgg_extract('add_salutation', $params, true);
		$params['add_mute_link'] = elgg_extract('add_mute_link', $params, $this->addMuteLink());

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

		$result = _elgg_services()->events->triggerResults('send', "notification:{$method}", $params, false);
		
		if ($this->getLogger()->isLoggable(LogLevel::INFO)) {
			$logger_data = print_r((array) $notification->toObject(), true);
			if ($result) {
				$this->getLogger()->info('Notification sent: ' . $logger_data);
			} else {
				$this->getLogger()->info('Notification was not sent: ' . $logger_data);
			}
		}
		
		return $result;
	}
	
	/**
	 * Prepares a notification for delivery
	 *
	 * @param array $params Parameters to initialize notification with
	 *
	 * @return Notification
	 * @throws RuntimeException
	 */
	final protected function prepareNotification(array $params): Notification {
		$notification = new Notification($params['sender'], $params['recipient'], $params['language'], $params['subject'], $params['body'], $params['summary'], $params);

		$notification = _elgg_services()->events->triggerResults('prepare', 'notification', $params, $notification);
		if (!$notification instanceof Notification) {
			throw new RuntimeException("'prepare','notification' event must return an instance of " . Notification::class);
		}

		$type = 'notification:' . $this->event->getDescription();
		$notification = _elgg_services()->events->triggerResults('prepare', $type, $params, $notification);
		if (!$notification instanceof Notification) {
			throw new RuntimeException("'prepare','{$type}' event must return an instance of " . Notification::class);
		}

		if (elgg_extract('add_salutation', $notification->params) === true) {
			$viewtype = elgg_view_exists('notifications/body') ? '' : 'default';
			$notification->body = _elgg_view_under_viewtype('notifications/body', ['notification' => $notification], $viewtype);
		}
		
		$notification = _elgg_services()->events->triggerResults('format', "notification:{$params['method']}", [], $notification);
		if (!$notification instanceof Notification) {
			throw new RuntimeException("'format','notification:{$params['method']}' event must return an instance of " . Notification::class);
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
		$actor = $this->event->getActor() ?: null;
		$object = $this->event->getObject();
		
		// Check custom notification subject for the action/type/subtype combination
		$subject_key = "notification:{$this->event->getDescription()}:subject";
		if (_elgg_services()->translator->languageKeyExists($subject_key)) {
			return _elgg_services()->translator->translate($subject_key, [
				$actor?->getDisplayName(),
				$object instanceof \ElggEntity ? $object->getDisplayName() : '',
			]);
		}

		// Fall back to default subject
		return _elgg_services()->translator->translate('notification:subject', [$actor?->getDisplayName()]);
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
		$actor = $this->event->getActor() ?: null;
		$object = $this->event->getObject() ?: null;
		
		// Check custom notification body for the action/type/subtype combination
		$body_key = "notification:{$this->event->getDescription()}:body";
		if (_elgg_services()->translator->languageKeyExists($body_key)) {
			if ($object instanceof \ElggEntity) {
				$display_name = $object->getDisplayName();
				$container_name = '';
				$container = $object->getContainerEntity();
				if ($container instanceof \ElggEntity) {
					$container_name = $container->getDisplayName();
				}
			} else {
				$display_name = '';
				$container_name = '';
			}

			return _elgg_services()->translator->translate($body_key, [
				$actor?->getDisplayName(),
				$display_name,
				$container_name,
				$object?->description,
				$object?->getURL(),
			]);
		}

		// Fall back to default body
		return _elgg_services()->translator->translate('notification:body', [$object->getURL()]);
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
		$object = $this->event->getObject() ?: null;
		
		return (string) $object?->getURL();
	}
	
	/**
	 * Get the acting user from the notification event
	 *
	 * @return null|\ElggUser
	 * @since 6.1
	 */
	protected function getEventActor(): ?\ElggUser {
		$actor = $this->event->getActor();
		
		return $actor instanceof \ElggUser ? $actor : null;
	}
	
	/**
	 * Get the entity from the notification event
	 *
	 * @return null|\ElggEntity
	 * @since 6.1
	 */
	protected function getEventEntity(): ?\ElggEntity {
		$object = $this->event->getObject();
		
		return $object instanceof \ElggEntity ? $object : null;
	}
	
	/**
	 * Is this event configurable by the user on the notification settings page
	 *
	 * @return bool
	 */
	public static function isConfigurableByUser(): bool {
		return true;
	}
	
	/**
	 * Can this event be configured for a specific entity
	 *
	 * For example this can be based on a group tools option which is enabled or not
	 *
	 * @param \ElggEntity $entity the entity to check for
	 *
	 * @return bool
	 * @since 4.1
	 */
	final public static function isConfigurableForEntity(\ElggEntity $entity): bool {
		if ($entity instanceof \ElggUser) {
			return static::isConfigurableForUser($entity);
		} elseif ($entity instanceof \ElggGroup) {
			return static::isConfigurableForGroup($entity);
		}
		
		return true;
	}
	
	/**
	 * Can this event be configured for a specific user
	 *
	 * @param \ElggUser $user the user to check for
	 *
	 * @return bool
	 * @since 4.1
	 */
	protected static function isConfigurableForUser(\ElggUser $user): bool {
		return true;
	}
	
	/**
	 * Can this event be configured for a specific group
	 *
	 * For example this can be based on a group tools option which is enabled or not
	 *
	 * @param \ElggGroup $group the group to check for
	 *
	 * @return bool
	 * @since 4.1
	 */
	protected static function isConfigurableForGroup(\ElggGroup $group): bool {
		return true;
	}
	
	/**
	 * Add a mute link in the email notification
	 *
	 * @return bool
	 */
	protected function addMuteLink(): bool {
		return true;
	}
}
