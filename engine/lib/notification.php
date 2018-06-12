<?php
/**
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
 *    'recipient', and 'language'. The event is an \Elgg\Notifications\Event
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
 *
 *
 * @package Elgg.Core
 * @subpackage Notifications
 */

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
 *                                An event is usually described by the first string passed
 *                                to elgg_trigger_event(). Examples include
 *                                'create', 'update', and 'publish'. The default is 'create'.
 * @return void
 * @since 1.9
 */
function elgg_register_notification_event($object_type, $object_subtype, array $actions = []) {
	_elgg_services()->notifications->registerEvent($object_type, $object_subtype, $actions);
}

/**
 * Unregister a notification event
 *
 * @param string $object_type    'object', 'user', 'group', 'site'
 * @param string $object_subtype The type of the entity
 * @return bool
 * @since 1.9
 */
function elgg_unregister_notification_event($object_type, $object_subtype) {
	return _elgg_services()->notifications->unregisterEvent($object_type, $object_subtype);
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
function elgg_register_notification_method($name) {
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
function elgg_get_notification_methods() {
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
function elgg_unregister_notification_method($name) {
	return _elgg_services()->notifications->unregisterMethod($name);
}

/**
 * Subscribe a user to notifications about a target entity
 *
 * @param int    $user_guid   The GUID of the user to subscribe to notifications
 * @param string $method      The delivery method of the notifications
 * @param int    $target_guid The entity to receive notifications about
 * @return bool
 * @since 1.9
 */
function elgg_add_subscription($user_guid, $method, $target_guid) {
	$methods = _elgg_services()->notifications->getMethods();
	$db = _elgg_services()->db;
	$subs = new \Elgg\Notifications\SubscriptionsService($db, $methods);
	return $subs->addSubscription($user_guid, $method, $target_guid);
}

/**
 * Unsubscribe a user to notifications about a target entity
 *
 * @param int    $user_guid   The GUID of the user to unsubscribe to notifications
 * @param string $method      The delivery method of the notifications to stop
 * @param int    $target_guid The entity to stop receiving notifications about
 * @return bool
 * @since 1.9
 */
function elgg_remove_subscription($user_guid, $method, $target_guid) {
	$methods = _elgg_services()->notifications->getMethods();
	$db = _elgg_services()->db;
	$subs = new \Elgg\Notifications\SubscriptionsService($db, $methods);
	return $subs->removeSubscription($user_guid, $method, $target_guid);
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
 * @return array User GUIDs (keys) and their subscription types (values).
 * @since 1.9
 * @todo deprecate once new subscriptions system has been added
 */
function elgg_get_subscriptions_for_container($container_guid) {
	$methods = _elgg_services()->notifications->getMethods();
	$db = _elgg_services()->db;
	$subs = new \Elgg\Notifications\SubscriptionsService($db, $methods);
	return $subs->getSubscriptionsForContainer($container_guid);
}

/**
 * Queue a notification event for later handling
 *
 * Checks to see if this event has been registered for notifications.
 * If so, it adds the event to a notification queue.
 *
 * This function triggers the 'enqueue', 'notification' hook.
 *
 * @param string    $action The name of the action
 * @param string    $type   The type of the object
 * @param \ElggData $object The object of the event
 * @return void
 * @access private
 * @since 1.9
 */
function _elgg_enqueue_notification_event($action, $type, $object) {
	_elgg_services()->notifications->enqueueEvent($action, $type, $object);
}

/**
 * Process notification queue
 *
 * @return void
 *
 * @access private
 */
function _elgg_notifications_cron() {
	// calculate when we should stop
	// @todo make configurable?
	$stop_time = time() + 45;
	_elgg_services()->notifications->processQueue($stop_time);
}

/**
 * Send an email notification
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param bool   $result Has anyone sent a message yet?
 * @param array  $params Hook parameters
 * @return bool
 * @access private
 */
function _elgg_send_email_notification($hook, $type, $result, $params) {
	
	if ($result === true) {
		// assume someone else already sent the message
		return;
	}

	$message = $params['notification'];
	if (!$message instanceof \Elgg\Notifications\Notification) {
		return false;
	}

	$sender = $message->getSender();
	$recipient = $message->getRecipient();

	if (!$sender) {
		return false;
	}

	if (!$recipient || !$recipient->email) {
		return false;
	}

	$email = \Elgg\Email::factory([
		'from' => $sender,
		'to' => $recipient,
		'subject' => $message->subject,
		'body' => $message->body,
		'params' => $message->params,
	]);

	return _elgg_services()->emails->send($email);
}

/**
 * Adds default Message-ID header to all e-mails
 *
 * @param string      $hook  "prepare"
 * @param string      $type  "system:email"
 * @param \Elgg\Email $email Email instance
 *
 * @see    https://tools.ietf.org/html/rfc5322#section-3.6.4
 *
 * @return array
 * @access private
 */
function _elgg_notifications_smtp_default_message_id_header($hook, $type, $email) {
	
	if (!$email instanceof \Elgg\Email) {
		return;
	}
	
	$hostname = parse_url(elgg_get_site_url(), PHP_URL_HOST);
	$url_path = parse_url(elgg_get_site_url(), PHP_URL_PATH);
	
	$mt = microtime(true);
	
	$email->addHeader('Message-ID', "{$url_path}.default.{$mt}@{$hostname}");
	
	return $email;
}

/**
 * Adds default thread SMTP headers to group messages correctly.
 * Note that it won't be sufficient for some email clients. Ie. Gmail is looking at message subject anyway.
 *
 * @param string      $hook  "prepare"
 * @param string      $type  "system:email"
 * @param \Elgg\Email $email Email instance
 *
 * @return array
 * @access private
 */
function _elgg_notifications_smtp_thread_headers($hook, $type, $email) {

	if (!$email instanceof \Elgg\Email) {
		return;
	}

	$notificationParams = $email->getParams();

	$notification = elgg_extract('notification', $notificationParams);
	if (!$notification instanceof \Elgg\Notifications\Notification) {
		return;
	}

	$object = elgg_extract('object', $notification->params);
	if (!$object instanceof \ElggEntity) {
		return;
	}

	$event = elgg_extract('event', $notification->params);
	if (!$event instanceof \Elgg\Notifications\NotificationEvent) {
		return;
	}

	$hostname = parse_url(elgg_get_site_url(), PHP_URL_HOST);
	$urlPath = parse_url(elgg_get_site_url(), PHP_URL_PATH);

	if ($event->getAction() === 'create') {
		// create event happens once per entity and we need to guarantee message id uniqueness
		// and at the same time have thread message id that we don't need to store
		$messageId = "{$urlPath}.entity.{$object->guid}@{$hostname}";
	} else {
		$mt = microtime(true);
		$messageId = "{$urlPath}.entity.{$object->guid}.$mt@{$hostname}";
	}

	$email->addHeader("Message-ID", $messageId);

	// let's just thread comments by default
	$container = $object->getContainerEntity();
	if ($container instanceof \ElggEntity && $object instanceof \ElggComment) {
		$threadMessageId = "<{$urlPath}.entity.{$container->guid}@{$hostname}>";
		$email->addHeader('In-Reply-To', $threadMessageId);
		$email->addHeader('References', $threadMessageId);
	}

	return $email;
}

/**
 * Notification init
 *
 * @return void
 *
 * @access private
 */
function _elgg_notifications_init() {
	elgg_register_plugin_hook_handler('cron', 'minute', '_elgg_notifications_cron', 100);
	elgg_register_event_handler('all', 'all', '_elgg_enqueue_notification_event', 700);

	// add email notifications
	elgg_register_notification_method('email');
	elgg_register_plugin_hook_handler('send', 'notification:email', '_elgg_send_email_notification');
	elgg_register_plugin_hook_handler('prepare', 'system:email', '_elgg_notifications_smtp_default_message_id_header', 1);
	elgg_register_plugin_hook_handler('prepare', 'system:email', '_elgg_notifications_smtp_thread_headers');

	// add ability to set personal notification method
	elgg_extend_view('forms/usersettings/save', 'core/settings/account/notifications');
	elgg_register_plugin_hook_handler('usersettings:save', 'user', '_elgg_save_notification_user_settings');
}

/**
 * Notify a user via their preferences.
 *
 * @param mixed  $to               Either a guid or an array of guid's to notify.
 * @param int    $from             GUID of the sender, which may be a user, site or object.
 * @param string $subject          Message subject.
 * @param string $message          Message body.
 * @param array  $params           Misc additional parameters specific to various methods.
 * @param mixed  $methods_override A string, or an array of strings specifying the delivery
 *                                 methods to use - or leave blank for delivery using the
 *                                 user's chosen delivery methods.
 *
 * @return array Compound array of each delivery user/delivery method's success or failure.
 * @access private
 */
function _elgg_notify_user($to, $from, $subject, $message, array $params = null, $methods_override = "") {

	$notify_service = _elgg_services()->notifications;

	// Sanitise
	if (!is_array($to)) {
		$to = [(int) $to];
	}
	$from = (int) $from;
	//$subject = sanitise_string($subject);
	// Get notification methods
	if (($methods_override) && (!is_array($methods_override))) {
		$methods_override = [$methods_override];
	}

	$result = [];

	foreach ($to as $guid) {
		// Results for a user are...
		$result[$guid] = [];

		$recipient = get_entity($guid);
		if (empty($recipient)) {
			continue;
		}

		// Are we overriding delivery?
		$methods = $methods_override;
		if (empty($methods)) {
			$methods = [];

			if (!($recipient instanceof ElggUser)) {
				// not sending to a user so can't get user notification settings
				continue;
			}

			$tmp = $recipient->getNotificationSettings();
			if (empty($tmp)) {
				// user has no notification settings
				continue;
			}

			foreach ($tmp as $k => $v) {
				// Add method if method is turned on for user!
				if ($v) {
					$methods[] = $k;
				}
			}
		}

		if (empty($methods)) {
			continue;
		}

		// Deliver
		foreach ($methods as $method) {
			$handler = $notify_service->getDeprecatedHandler($method);
			/* @var callable $handler */
			if (!$handler || !is_callable($handler)) {
				elgg_log("No handler registered for the method $method", 'INFO');
				continue;
			}

			elgg_log("Sending message to $guid using $method");

			// Trigger handler and retrieve result.
			try {
				$result[$guid][$method] = call_user_func(
					$handler,
					$from ? get_entity($from) : null,
					get_entity($guid),
					$subject,
					$message,
					$params
				);
			} catch (Exception $e) {
				elgg_log($e, 'ERROR');
			}
		}
	}

	return $result;
}

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
 * Catch NotificationException to trap errors.
 *
 * @package Elgg.Core
 * @subpackage Notifications
 */

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
 *                                 object => null|\ElggEntity|\ElggAnnotation The object that
 *                                           is triggering the notification.
 *
 *                                 action => null|string Word that describes the action that
 *                                           is triggering the notification (e.g. "create"
 *                                           or "update").
 *
 *                                 summary => null|string Summary that notification plugins
 *                                            can use alongside the notification title and body.
 *
 * @param mixed  $methods_override A string, or an array of strings specifying the delivery
 *                                 methods to use - or leave blank for delivery using the
 *                                 user's chosen delivery methods.
 *
 * @return array Compound array of each delivery user/delivery method's success or failure.
 * @throws NotificationException
 */
function notify_user($to, $from = 0, $subject = '', $message = '', array $params = [], $methods_override = null) {

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
function elgg_send_email($email) {

	if (!$email instanceof \Elgg\Email) {
		elgg_deprecated_notice(__FUNCTION__ . '
			 should be given a single instance of \Elgg\Email
		', '3.0');

		$args = func_get_args();
		$email = \Elgg\Email::factory([
			'from' => array_shift($args),
			'to' => array_shift($args),
			'subject' => array_shift($args),
			'body' => array_shift($args),
			'params' => array_shift($args) ? : [],
		]);
	}

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
 * @param \Zend\Mail\Transport\TransportInterface $mailer Transport
 * @return void
 */
function elgg_set_email_transport(\Zend\Mail\Transport\TransportInterface $mailer) {
	_elgg_services()->setValue('mailer', $mailer);
}

/**
 * Save personal notification settings - input comes from request
 *
 * @return void
 * @access private
 */
function _elgg_save_notification_user_settings() {

	$user = elgg_get_logged_in_user_entity();
	if (!$user) {
		return;
	}

	$method = get_input('method');

	$current_settings = $user->getNotificationSettings();

	$result = false;
	foreach ($method as $k => $v) {
		// check if setting has changed and skip if not
		if ($current_settings[$k] == ($v == 'yes')) {
			continue;
		}

		$result = $user->setNotificationSetting($k, ($v == 'yes'));
		if (!$result) {
			register_error(elgg_echo('notifications:usersettings:save:fail'));
		}
	}

	if ($result) {
		system_message(elgg_echo('notifications:usersettings:save:ok'));
	}
}

/**
 * Register unit tests
 *
 * @param string $hook  'unit_test'
 * @param string $type  'system'
 * @param array  $tests current return value
 *
 * @return array
 *
 * @access private
 * @codeCoverageIgnore
 */
function _elgg_notifications_test($hook, $type, $tests) {
	$tests[] = ElggCoreDatabaseQueueTest::class;
	return $tests;
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_notifications_init');

	$hooks->registerHandler('unit_test', 'system', '_elgg_notifications_test');
};
