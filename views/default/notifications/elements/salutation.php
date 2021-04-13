<?php
/**
 * Output the salutation for a notification
 *
 * @uses $vars['notification'] Notification to return salutation for
 * @uses $vars['recipient']    Recipient will be overridden by Notification->getRecipient() if provided
 * @uses $vars['language']     Salutation language will use Notification or Recipient language if available
 */

use Elgg\Notifications\Notification;

$recipient = elgg_extract('recipient', $vars);
$language = elgg_extract('language', $vars);

$notification = elgg_extract('notification', $vars);
if ($notification instanceof Notification) {
	$recipient = $notification->getRecipient();
	$language = $notification->language;
}

if (!$recipient instanceof ElggEntity) {
	return;
}

if (empty($language) && $recipient instanceof ElggUser) {
	$language = $recipient->getLanguage();
}

echo elgg_echo('notification:default:salutation', [$recipient->getDisplayName()], $language);
