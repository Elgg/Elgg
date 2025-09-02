<?php
/**
 * Bundle all functions that have been deprecated in Elgg 6.3
 */

/**
 * Logs $value to PHP's {@link error_log()}
 *
 * A 'debug', log' event is triggered. If a handler returns
 * false, it will stop the default logging method.
 *
 * @note Use the developers plugin to display logs
 *
 * @param mixed $value The value
 * @return void
 * @since 1.7.0
 * @deprecated 6.3 Use elgg_log()
 */
function elgg_dump($value): void {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated, use elgg_log()', '6.3');
	
	_elgg_services()->logger->error($value);
}

/**
 * Notify a user via their preferences.
 *
 * @param mixed  $to               Either a guid or an array of guid's to notify.
 * @param int    $from             GUID of the sender, which may be a user, site or object.
 * @param string $subject          Message subject.
 * @param string $message          Message body.
 * @param array  $params           Misc additional parameters specific to various methods.
 *
 *                                 By default Elgg core supports three parameters, which give
 *                                 notification plugins more control over the notifications:
 *
 *                                 object => null|\ElggEntity|\ElggAnnotation The object that is triggering the notification.
 *
 *                                 action => null|string Word that describes the action that is triggering the notification (e.g. "create" or "update").
 *
 *                                 summary => null|string Summary that notification plugins can use alongside the notification title and body.
 *
 * @param mixed  $methods_override A string, or an array of strings specifying the delivery
 *                                 methods to use - or leave blank for delivery using the
 *                                 user's chosen delivery methods.
 *
 * @return array Compound array of each delivery user/delivery method's success or failure.
 * @deprecated 6.3 use elgg_notify_user()
 */
function notify_user(int|array $to, int $from = 0, string $subject = '', string $message = '', array $params = [], $methods_override = null): array {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated use "elgg_notify_user()"', '6.3');
	
	$params['subject'] = $subject;
	$params['body'] = $message;
	$params['methods_override'] = $methods_override ? (array) $methods_override : null;
	
	if (!empty($from)) {
		$sender = get_entity($from);
	} else {
		$sender = elgg_get_site_entity();
	}
	
	if (!$sender instanceof \ElggEntity) {
		return [];
	}
	
	$recipients = [];
	$to = (array) $to;
	foreach ($to as $guid) {
		$recipient = get_entity($guid);
		if (!$recipient instanceof \ElggEntity) {
			continue;
		}
		
		$recipients[] = $recipient;
	}
	
	return _elgg_services()->notifications->sendInstantNotifications($sender, $recipients, $params);
}
