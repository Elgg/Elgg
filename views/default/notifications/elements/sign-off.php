<?php
/**
 * Output the sign-off for a notification
 *
 * @uses $vars['notification'] Notification to return salutation for
 * @uses $vars['recipient']    Recipient will be overridden by Notification->getRecipient() if provided
 * @uses $vars['language']     Sign-off language will use Notification language if available
 */

use Elgg\Notifications\Notification;

$recipient = elgg_extract('recipient', $vars);
$language = elgg_extract('language', $vars);

$notification = elgg_extract('notification', $vars);
if ($notification instanceof Notification) {
	$recipient = $notification->getRecipient();
	$language = $notification->language;
}

if (empty($language) && $recipient instanceof ElggUser) {
	$language = $recipient->getLanguage();
}

echo elgg_echo('notification:default:sign-off', [elgg_get_site_entity()->getDisplayName()], $language);
