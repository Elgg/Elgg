<?php

namespace Elgg\Notifications;

/**
 * Notification Event Handler for instant notifications
 *
 * @since 4.0
 */
final class InstantNotificationEventHandler extends NotificationEventHandler {

	/**
	 * {@inheritDoc}
	 */
	public function getSubscriptions(): array {
		$subscriptions = [];
		
		$methods_override = (array) elgg_extract('methods_override', $this->params, []);
		$recipients = (array) elgg_extract('recipients', $this->params, []);
		
		foreach ($recipients as $user) {
			if (!empty($methods_override)) {
				$subscriptions[$user->guid] = $methods_override;
				continue;
			}
			
			// get user default preferences
			$subscriptions[$user->guid] = array_keys(array_filter($user->getNotificationSettings()));
		}

		return $subscriptions;
	}
}
