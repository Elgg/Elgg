<?php
/**
 * View to wrap notification body with a generic salutation and sign-off
 *
 * @uses $vars['notification'] Notification to return new body for
 */

use Elgg\Notifications\Notification;

$notification = elgg_extract('notification', $vars);
if (!$notification instanceof Notification) {
	return;
}

$salutation = elgg_view('notifications/elements/salutation', $vars);
$body = $notification->body;
$signoff = elgg_view('notifications/elements/sign-off', $vars);

echo implode(PHP_EOL . PHP_EOL, array_filter([$salutation, $body, $signoff]));
