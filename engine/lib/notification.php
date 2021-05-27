<?php
/**
 * Notifications
 * This file contains classes and functions which allow plugins to register and send notifications.
 *
 * There are notification methods which are provided out of the box
 * (see notification_init() ). Each method is identified by a string, e.g. "email".
 *
 * To register an event use register_notification_handler() and pass the method name and a
 * handler function.
 *
 * To send a notification call notify() passing it the method you wish to use combined with a
 * number of method specific addressing parameters.
 *
 * Adding a New Notification Event
 * ===============================
 * 1. Register the event with elgg_register_notification_event()
 *
 * 2. Register for the notification message plugin hook:
 *    'prepare', 'notification:[event name]'. The event name is of the form
 *    [action]:[type]:[subtype]. For example, the publish event for a blog
 *    would be named 'publish:object:blog'.
 *
 *    The parameter array for the plugin hook has the keys 'event', 'method',
 *    'recipient', and 'language'. The event is an \Elgg\Notifications\SubscriptionNotificationEvent
 *    object and can provide access to the original object of the event through
 *    the method getObject() and the original actor through getActor().
 *
 *    The plugin hook callback modifies and returns a
 *    \Elgg\Notifications\Notification object that holds the message content.
 *
 *
 * Adding a Delivery Method
 * =========================
 * 1. Register the delivery method name with elgg_register_notification_method()
 *
 * 2. Register for the plugin hook for sending notifications:
 *    'send', 'notification:[method name]'. It receives the notification object
 *    of the namespace Elgg\Notifications;
 *
 *	  class Notification in the params array with the
 *    key 'notification'. The callback should return a boolean to indicate whether
 *    the message was sent.
 *
 *
 * Subscribing a User for Notifications
 * ====================================
 * Users subscribe to receive notifications based on container and delivery method.
 */

use Elgg\Notifications\NotificationEventHandler;

/**
 * Register a notification event
 *
 * Elgg sends notifications for the items that have been registered with this
 * function. For example, if you want notifications to be sent when a bookmark
 * has been created or updated, call the function like this:
 *
 * 	   elgg_register_notification_event('object', 'bookmarks', array('create', 'update'));
 *
 * @param string $object_type    'object', 'user', 'group', 'site'
 * @param string $object_subtype The subtype or name of the entity
 * @param array  $actions        Array of actions or empty array for the action event.
 *                               An event is usually described by the first string passed
 *                               to elgg_trigger_event(). Examples include
 *                               'create', 'update', and 'publish'. The default is 'create'.
 * @param string $handler        NotificationEventHandler classname
 *
 * @return void
 * @since 1.9
 */
function elgg_register_notification_event($object_type, $object_subtype, array $actions = [], string $handler = NotificationEventHandler::class): void {
	_elgg_services()->notifications->registerEvent($object_type, $object_subtype, $actions, $handler);
}

/**
 * Unregister a notification event
 *
 * @param string $object_type    'object', 'user', 'group', 'site'
 * @param string $object_subtype The type of the entity
 * @param array  $actions        The notification action to unregister, leave empty for all actions. Example ('create', 'delete', 'publish')
 *
 * @return bool
 * @since 1.9
 * @see elgg_register_notification_event()
 */
function elgg_unregister_notification_event($object_type, $object_subtype, array $actions = []): bool {
	return _elgg_services()->notifications->unregisterEvent($object_type, $object_subtype, $actions);
}

/**
 * Register a delivery method for notifications
 *
 * Register for the 'send', 'notification:[method name]' plugin hook to handle
 * sending a notification. A notification object is in the params array for the
 * hook with the key 'notification'. See \Elgg\Notifications\Notification.
 *
 * @param string $name The notification method name
 * @return void
 * @see elgg_unregister_notification_method()
 * @since 1.9
 */
function elgg_register_notification_method($name): void {
	_elgg_services()->notifications->registerMethod($name);
}

/**
 * Returns registered delivery methods for notifications
 * <code>
 *	[
 *		'email' => 'email',
 *		'sms' => 'sms',
 *	]
 * </code>
 *
 * @return array
 * @since 2.3
 */
function elgg_get_notification_methods(): array {
	return _elgg_services()->notifications->getMethods();
}

/**
 * Unregister a delivery method for notifications
 *
 * @param string $name The notification method name
 * @return bool
 * @see elgg_register_notification_method()
 * @since 1.9
 */
function elgg_unregister_notification_method($name): bool {
	return _elgg_services()->notifications->unregisterMethod($name);
}

/**
 * Get the registered notification events in the format
 *
 * array (
 * 		<type> => array (
 * 			<subtype> => array (
 * 				<action1>,
 * 				<action2>,
 * 			)
 * 		)
 * )
 *
 * @return array
 * @since 4.0
 */
function elgg_get_notification_events(): array {
	return _elgg_services()->notifications->getEvents();
}

/**
 * Get the subscriptions for the content created inside this container.
 *
 * The return array is of the form:
 *
 * array(
 *     <user guid> => array('email', 'sms', 'ajax'),
 * );
 *
 * @param int $container_guid GUID of the entity acting as a container
 *
 * @return array User GUIDs (keys) and their subscription types (values).
 * @since 1.9
 * @todo deprecate once new subscriptions system has been added
 */
function elgg_get_subscriptions_for_container(int $container_guid): array {
	$methods = _elgg_services()->notifications->getMethods();
	
	return _elgg_services()->subscriptions->getSubscriptionsForContainer($container_guid, $methods);
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
 */
function notify_user($to, $from = 0, $subject = '', $message = '', array $params = [], $methods_override = null): array {

	$params['subject'] = $subject;
	$params['body'] = $message;
	$params['methods_override'] = $methods_override;

	if ($from) {
		$sender = get_entity($from);
	} else {
		$sender = elgg_get_site_entity();
	}
	if (!$sender) {
		return [];
	}

	$recipients = [];
	$to = (array) $to;
	foreach ($to as $guid) {
		$recipient = get_entity($guid);
		if (!$recipient) {
			continue;
		}
		$recipients[] = $recipient;
	}

	return _elgg_services()->notifications->sendInstantNotifications($sender, $recipients, $params);
}

/**
 * Send an email to any email address
 *
 * @param \Elgg\Email $email Email
 * @return bool
 * @since 1.7.2
 */
function elgg_send_email(\Elgg\Email $email): bool {
	return _elgg_services()->emails->send($email);
}

/**
 * Replace default email transport
 *
 * @note If you are replacing the transport persistently, e.g. on each page request via
 * a plugin, avoid using plugin settings to store transport configuration, as it
 * may be expensive to fetch these settings. Instead, configure the transport
 * via elgg-config/settings.php or use site config DB storage.
 *
 * @param \Laminas\Mail\Transport\TransportInterface $mailer Transport
 * @return void
 */
function elgg_set_email_transport(\Laminas\Mail\Transport\TransportInterface $mailer): void {
	_elgg_services()->setValue('mailer', $mailer);
}
