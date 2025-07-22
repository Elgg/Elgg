<?php

namespace Elgg\Notifications;

/**
 * A notification event handler which isn't configurable by a user
 *
 * @since 6.3
 */
abstract class NonConfigurableNotificationEventHandler extends NotificationEventHandler {
	
	/**
	 * {@inheritdoc}
	 */
	final public static function isConfigurableByUser(): bool {
		return false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	final protected static function isConfigurableForUser(\ElggUser $user): bool {
		return false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	final protected static function isConfigurableForGroup(\ElggGroup $group): bool {
		return false;
	}
}
