<?php
/**
 * View to wrap notification body with a generic salutation and sign-off
 *
 * @uses $vars['notification'] Notification to return new body for
 * @uses $vars['body']         Notification body to use, will be overridden by Notification->body if provided
 */

use Elgg\Notifications\Notification;

$body = elgg_extract('body', $vars);

$notification = elgg_extract('notification', $vars);
if ($notification instanceof Notification) {
	$body = $notification->body;
}

$salutation = elgg_view('notifications/elements/salutation', $vars);

$signoff = elgg_view('notifications/elements/sign-off', $vars);

echo implode(PHP_EOL . PHP_EOL, array_filter([$salutation, $body, $signoff]));
