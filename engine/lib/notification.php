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
 *    'recipient', and 'language'. The event is an Elgg_Notifications_Event
 *    object and can provide access to the original object of the event through
 *    the method getObject() and the original actor through getActor().
 *
 *    The plugin hook callback modifies and returns a
 *    Elgg_Notifications_Notification object that holds the message content.
 *
 *
 * Adding a Delivery Method
 * =========================
 * 1. Register the delivery method name with elgg_register_notification_method()
 *
 * 2. Register for the plugin hook for sending notifications:
 *    'send', 'notification:[method name]'. It receives the notification object
 *    of the class Elgg_Notifications_Notification in the params array with the
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
function elgg_register_notification_event($object_type, $object_subtype, array $actions = array()) {
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
 * hook with the key 'notification'. See Elgg_Notifications_Notification.
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
	$subs = new Elgg_Notifications_SubscriptionsService($db, $methods);
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
	$subs = new Elgg_Notifications_SubscriptionsService($db, $methods);
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
	$subs = new Elgg_Notifications_SubscriptionsService($db, $methods);
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
 * @param string   $action The name of the action
 * @param string   $type   The type of the object
 * @param ElggData $object The object of the event
 * @return void
 * @access private
 * @since 1.9
 */
function _elgg_enqueue_notification_event($action, $type, $object) {
	_elgg_services()->notifications->enqueueEvent($action, $type, $object);
}

/**
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
	/* @var Elgg_Notifications_Notification $message */
	$message = $params['notification'];

	$sender = $message->getSender();
	$recipient = $message->getRecipient();

	if (!$sender) {
		return false;
	}

	if (!$recipient || !$recipient->email) {
		return false;
	}

	$to = $recipient->email;

	$site = elgg_get_site_entity();
	// If there's an email address, use it - but only if it's not from a user.
	if (!($sender instanceof ElggUser) && $sender->email) {
		$from = $sender->email;
	} else if ($site->email) {
		$from = $site->email;
	} else {
		// If all else fails, use the domain of the site.
		$from = 'noreply@' . $site->getDomain();
	}

	return elgg_send_email($from, $to, $message->subject, $message->body, $params);
}

/**
 * Adds default thread SMTP headers to group messages correctly.
 * Note that it won't be sufficient for some email clients. Ie. Gmail is looking at message subject anyway.
 *
 * @param string $hook        Equals to 'email'
 * @param string $type        Equals to 'system'
 * @param array  $returnvalue Array containing fields: 'to', 'from', 'subject', 'body', 'headers', 'params'
 * @param array  $params      The same value as $returnvalue
 * @return array
 * @access private
 */
function _elgg_notifications_smtp_thread_headers($hook, $type, $returnvalue, $params) {

	$notificationParams = elgg_extract('params', $returnvalue, array());
	/** @var Elgg_Notifications_Notification */
	$notification = elgg_extract('notification', $notificationParams);

	if (!($notification instanceof Elgg_Notifications_Notification)) {
		return $returnvalue;
	}

	$hostname = parse_url(elgg_get_site_url(), PHP_URL_HOST);
	$urlPath = parse_url(elgg_get_site_url(), PHP_URL_PATH);

	$object = elgg_extract('object', $notification->params);
	/** @var Elgg_Notifications_Event $event */
	$event = elgg_extract('event', $notification->params);

	if (($object instanceof ElggEntity) && ($event instanceof Elgg_Notifications_Event)) {
		if ($event->getAction() === 'create') {
			// create event happens once per entity and we need to guarantee message id uniqueness
			// and at the same time have thread message id that we don't need to store
			$messageId = "<{$urlPath}.entity.{$object->guid}@{$hostname}>";
		} else {
			$mt = microtime(true);
			$messageId = "<{$urlPath}.entity.{$object->guid}.$mt@{$hostname}>";
		}
		$returnvalue['headers']["Message-ID"] = $messageId;
		$container = $object->getContainerEntity();

		// let's just thread comments by default
		if (($container instanceof ElggEntity) && ($object instanceof ElggComment)) {

			$threadMessageId = "<{$urlPath}.entity.{$container->guid}@{$hostname}>";
			$returnvalue['headers']['In-Reply-To'] = $threadMessageId;
			$returnvalue['headers']['References'] = $threadMessageId;
		}
	}

	return $returnvalue;
}

/**
 * @access private
 */
function _elgg_notifications_init() {
	elgg_register_plugin_hook_handler('cron', 'minute', '_elgg_notifications_cron', 100);
	elgg_register_event_handler('all', 'all', '_elgg_enqueue_notification_event');

	// add email notifications
	elgg_register_notification_method('email');
	elgg_register_plugin_hook_handler('send', 'notification:email', '_elgg_send_email_notification');
	elgg_register_plugin_hook_handler('email', 'system', '_elgg_notifications_smtp_thread_headers');

	// add ability to set personal notification method
	elgg_extend_view('forms/account/settings', 'core/settings/account/notifications');
	elgg_register_plugin_hook_handler('usersettings:save', 'user', '_elgg_save_notification_user_settings');
}

elgg_register_event_handler('init', 'system', '_elgg_notifications_init');



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
		$to = array((int)$to);
	}
	$from = (int)$from;
	//$subject = sanitise_string($subject);

	// Get notification methods
	if (($methods_override) && (!is_array($methods_override))) {
		$methods_override = array($methods_override);
	}

	$result = array();

	foreach ($to as $guid) {
		// Results for a user are...
		$result[$guid] = array();

		if ($guid) { // Is the guid > 0?
			// Are we overriding delivery?
			$methods = $methods_override;
			if (!$methods) {
				$tmp = get_user_notification_settings($guid);
				$methods = array();
				// $tmp may be false. don't cast
				if (is_object($tmp)) {
					foreach ($tmp as $k => $v) {
						// Add method if method is turned on for user!
						if ($v) {
							$methods[] = $k;
						}
					}
				}
			}

			if ($methods) {
				// Deliver
				foreach ($methods as $method) {

					$handler = $notify_service->getDeprecatedHandler($method);
					/* @var callable $handler */
					if (!$handler || !is_callable($handler)) {
						error_log("No handler registered for the method $method", 'WARNING');
						continue;
					}

					elgg_log("Sending message to $guid using $method");

					// Trigger handler and retrieve result.
					try {
						$result[$guid][$method] = call_user_func($handler,
							$from ? get_entity($from) : null,
							get_entity($guid),
							$subject,
							$message,
							$params
						);
					} catch (Exception $e) {
						error_log($e->getMessage());
					}
				}
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
 *                                 object => null|ElggEntity|ElggAnnotation The object that
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
function notify_user($to, $from, $subject, $message, array $params = array(), $methods_override = "") {

	if (!is_array($to)) {
		$to = array((int)$to);
	}
	$from = (int)$from;
	$from = get_entity($from) ? $from : elgg_get_site_entity()->guid;
	$sender = get_entity($from);
	$summary = elgg_extract('summary', $params, '');

	// Get notification methods
	if (($methods_override) && (!is_array($methods_override))) {
		$methods_override = array($methods_override);
	}

	$result = array();

	$available_methods = _elgg_services()->notifications->getMethods();
	if (!$available_methods) {
		// There are no notifications methods to use
		return $result;
	}

	// temporary backward compatibility for 1.8 and earlier notifications
	$event = null;
	if (isset($params['object']) && isset($params['action'])) {
		$event = new Elgg_Notifications_Event($params['object'], $params['action'], $sender);
	}
	$params['event'] = $event;

	foreach ($to as $guid) {
		// Results for a user are...
		$result[$guid] = array();

		if ($guid) { // Is the guid > 0?
			// Are we overriding delivery?
			$methods = $methods_override;
			if (!$methods) {
				$tmp = (array)get_user_notification_settings($guid);
				$methods = array();
				foreach ($tmp as $k => $v) {
					// Add method if method is turned on for user!
					if ($v) {
						$methods[] = $k;
					}
				}
			}

			if ($methods) {
				// Deliver
				foreach ($methods as $method) {
					if (!in_array($method, $available_methods)) {
						// This method was available the last time the user saved their
						// notification settings. It's however currently disabled.
						continue;
					}

					if (_elgg_services()->hooks->hasHandler('send', "notification:$method")) {
						// 1.9 style notification handler
						$recipient = get_entity($guid);
						if (!$recipient) {
							continue;
						}
						$language = $recipient->language;
						$notification = new Elgg_Notifications_Notification($sender, $recipient, $language, $subject, $message, $summary, $params);
						$params['notification'] = $notification;
						$result[$guid][$method] = _elgg_services()->hooks->trigger('send', "notification:$method", $params, false);
					} else {
						$result[$guid][$method] = _elgg_notify_user($guid, $from, $subject, $message, $params, array($method));
					}
				}
			}
		}
	}

	return $result;
}

/**
 * Get the notification settings for a given user.
 *
 * @param int $user_guid The user id
 *
 * @return stdClass|false
 */
function get_user_notification_settings($user_guid = 0) {
	$user_guid = (int)$user_guid;

	if ($user_guid == 0) {
		$user_guid = elgg_get_logged_in_user_guid();
	}

	// @todo: there should be a better way now that metadata is cached. E.g. just query for MD names, then
	// query user object directly
	$all_metadata = elgg_get_metadata(array(
		'guid' => $user_guid,
		'limit' => 0
	));
	if ($all_metadata) {
		$prefix = "notification:method:";
		$return = new stdClass;

		foreach ($all_metadata as $meta) {
			$name = substr($meta->name, strlen($prefix));
			$value = $meta->value;

			if (strpos($meta->name, $prefix) === 0) {
				$return->$name = $value;
			}
		}

		return $return;
	}

	return false;
}

/**
 * Set a user notification pref.
 *
 * @param int    $user_guid The user id.
 * @param string $method    The delivery method (eg. email)
 * @param bool   $value     On(true) or off(false).
 *
 * @return bool
 */
function set_user_notification_setting($user_guid, $method, $value) {
	$user_guid = (int)$user_guid;
	$method = sanitise_string($method);

	$user = get_entity($user_guid);
	if (!$user) {
		$user = elgg_get_logged_in_user_entity();
	}

	if (($user) && ($user instanceof ElggUser)) {
		$prefix = "notification:method:$method";
		$user->$prefix = $value;
		$user->save();

		return true;
	}

	return false;
}

/**
 * Send an email to any email address
 *
 * @param string $from    Email address or string: "name <email>"
 * @param string $to      Email address or string: "name <email>"
 * @param string $subject The subject of the message
 * @param string $body    The message body
 * @param array  $params  Optional parameters (none used in this function)
 *
 * @return bool
 * @throws NotificationException
 * @since 1.7.2
 */
function elgg_send_email($from, $to, $subject, $body, array $params = null) {
	global $CONFIG;

	if (!$from) {
		$msg = "Missing a required parameter, '" . 'from' . "'";
		throw new NotificationException($msg);
	}

	if (!$to) {
		$msg = "Missing a required parameter, '" . 'to' . "'";
		throw new NotificationException($msg);
	}

	$headers = array(
		"Content-Type" => "text/plain; charset=UTF-8; format=flowed",
		"MIME-Version" => "1.0",
		"Content-Transfer-Encoding" => "8bit",
	);

	// return true/false to stop elgg_send_email() from sending
	$mail_params = array(
		'to' => $to,
		'from' => $from,
		'subject' => $subject,
		'body' => $body,
		'headers' => $headers,
		'params' => $params,
	);

	// $mail_params is passed as both params and return value. The former is for backwards
	// compatibility. The latter is so handlers can now alter the contents/headers of
	// the email by returning the array
	$result = elgg_trigger_plugin_hook('email', 'system', $mail_params, $mail_params);
	if (is_array($result)) {
		foreach (array('to', 'from', 'subject', 'body', 'headers') as $key) {
			if (isset($result[$key])) {
				${$key} = $result[$key];
			}
		}
	} elseif ($result !== null) {
		return $result;
	}

	$header_eol = "\r\n";
	if (isset($CONFIG->broken_mta) && $CONFIG->broken_mta) {
		// Allow non-RFC 2822 mail headers to support some broken MTAs
		$header_eol = "\n";
	}

	// Windows is somewhat broken, so we use just address for to and from
	if (strtolower(substr(PHP_OS, 0, 3)) == 'win') {
		// strip name from to and from
		if (strpos($to, '<')) {
			preg_match('/<(.*)>/', $to, $matches);
			$to = $matches[1];
		}
		if (strpos($from, '<')) {
			preg_match('/<(.*)>/', $from, $matches);
			$from = $matches[1];
		}
	}

	// make sure From is set
	if (empty($headers['From'])) {
		$headers['From'] = $from;
	}

	// stringify headers
	$headers_string = '';
	foreach ($headers as $key => $value) {
		$headers_string .= "$key: $value{$header_eol}";
	}

	// Sanitise subject by stripping line endings
	$subject = preg_replace("/(\r\n|\r|\n)/", " ", $subject);
	// this is because Elgg encodes everything and matches what is done with body
	$subject = html_entity_decode($subject, ENT_QUOTES, 'UTF-8'); // Decode any html entities
	if (is_callable('mb_encode_mimeheader')) {
		$subject = mb_encode_mimeheader($subject, "UTF-8", "B");
	}

	// Format message
	$body = html_entity_decode($body, ENT_QUOTES, 'UTF-8'); // Decode any html entities
	$body = elgg_strip_tags($body); // Strip tags from message
	$body = preg_replace("/(\r\n|\r)/", "\n", $body); // Convert to unix line endings in body
	$body = preg_replace("/^From/", ">From", $body); // Change lines starting with From to >From
	$body = wordwrap($body);

	return mail($to, $subject, $body, $headers_string);
}

/**
 * Save personal notification settings - input comes from request
 *
 * @return void
 * @access private
 */
function _elgg_save_notification_user_settings() {
	$method = get_input('method');

	$current_settings = get_user_notification_settings();

	$result = false;
	foreach ($method as $k => $v) {
		// check if setting has changed and skip if not
		if ($current_settings->$k == ($v == 'yes')) {
			continue;
		}

		$result = set_user_notification_setting(elgg_get_logged_in_user_guid(), $k, ($v == 'yes') ? true : false);

		if (!$result) {
			register_error(elgg_echo('notifications:usersettings:save:fail'));
		}
	}

	if ($result) {
		system_message(elgg_echo('notifications:usersettings:save:ok'));
	}
}

/**
 * @access private
 */
function _elgg_notifications_test($hook, $type, $tests) {
	global $CONFIG;
	$tests[] = "{$CONFIG->path}engine/tests/ElggCoreDatabaseQueueTest.php";
	return $tests;
}

elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_notifications_test');
