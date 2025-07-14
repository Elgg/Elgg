<?php

namespace Elgg\Notifications;

/**
 * Notification Event Handler for instant notifications
 *
 * @since 4.0
 */
class InstantNotificationEventHandler extends NonConfigurableNotificationEventHandler {
	
	/**
	 * @var \ElggUser[] recipients
	 */
	protected array $recipients = [];
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct(NotificationEvent $event, NotificationsService $service, array $params = []) {
		$recipients = elgg_extract('recipients', $params);
		unset($params['recipients']);
		
		parent::__construct($event, $service, $params);
		
		if (!empty($recipients) && is_array($recipients)) {
			$this->setRecipients($recipients);
		}
	}
	
	/**
	 * Set the recipients of the notification
	 *
	 * @param \ElggUser[] $recipients array recipients
	 *
	 * @return void
	 */
	final public function setRecipients(array $recipients): void {
		$this->recipients = array_filter($recipients, function($e) {
			return ($e instanceof \ElggUser);
		});
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getSubscriptions(): array {
		$subscriptions = [];
		
		$methods_override = $this->getMethodsOverride() ?: $this->getNotificationMethods();
		
		foreach ($this->recipients as $user) {
			if (!empty($methods_override)) {
				$subscriptions[$user->guid] = $methods_override;
				continue;
			}
			
			// get user default preferences
			$subscriptions[$user->guid] = array_keys(array_filter($user->getNotificationSettings()));
		}

		return $subscriptions;
	}
	
	/**
	 * {@inheritdoc}
	 */
	final protected function filterMutedSubscriptions(): bool {
		return false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	final protected function addMuteLink(): bool {
		return false;
	}
}
