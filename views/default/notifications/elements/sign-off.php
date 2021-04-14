<?php
/**
 * Output the sign-off for a notification
 */

use Elgg\Notifications\Notification;

$notification = elgg_extract('notification', $vars);
if (!$notification instanceof Notification) {
	return;
}

echo elgg_echo('notification:default:sign-off', [elgg_get_site_entity()->getDisplayName()], $notification->language);
