<?php
/**
 * Output the salutation for a notification
 *
 * @uses $vars['notification'] Notification to return salutation for
 */

use Elgg\Notifications\Notification;

$notification = elgg_extract('notification', $vars);
if (!$notification instanceof Notification) {
	return;
}

$recipient = $notification->getRecipient();

echo elgg_echo('notification:default:salutation', [$recipient->getDisplayName()], $notification->language);
