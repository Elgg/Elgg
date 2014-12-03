Notifications
#############

There are two ways to send notifications in Elgg:
 - Instant notifications
 - Event-based notifications send using a notifications queue

.. contents:: Contents
   :local:
   :depth: 1

Instant notifications
=====================

The generic method to send a notification to a user is via the function `notify_user()`__.
It is normally used when we want to notify only a single user. Notification like
this might for example inform that someone has liked or commented the user's post.

The function usually gets called in an :doc:`action <actions>` file.

__ http://reference.elgg.org/notification_8php.html#a9d8de7faa63baf2dcd5d42eb8f76eaa1

Example:
--------

In this example a user (``$user``) is triggering an action to rate a post created
by another user (``$owner``). After saving the rating (``ElggAnnotation $rating``)
to database, we could use the following code to send a notification about the new
rating to the owner.

.. code-block:: php

	// Subject of the notification
	$subject = elgg_echo('ratings:notification:subject', array(), $owner->language);

	// Summary of the notification
	$summary = elgg_echo('ratings:notification:summary', array($user->name), $owner->language);

	// Body of the notification message
	$subject = elgg_echo('ratings:notification:body', array(
		$user->name,
		$owner->name,
		$rating->getValue() // A value between 1-5
	), $owner->language);

	$params = array(
		'object' => $rating,
		'action' => 'create',
		'summary' => $summary
	);

	// Send the notification
	notify_user($user->guid, $owner->guid, $subject, $body, $params);

.. note::

	The language used by the recipient isn't necessarily the same as the language of the person
	who triggers the notification. Therefore you must always remember to pass the recipient's
	language as the third parameter to ``elgg_echo()``.

.. note::

	The ``'summary'`` parameter is meant for notification plugins that only want to display
	a short message instead of both the subject and the body. Therefore the summary should
	be terse but still contain all necessary information.

Enqueued notifications
======================

On large sites there may be many users who have subscribed to receive notifications
about a particular event. Sending notifications immediately when a user triggers
such an event might remarkably slow down page loading speed. This is why sending
of such notifications shoud be left for Elgg's notification queue.

New notification events can be registered with the `elgg_register_notification_event()`__
function. Notifications about registered events will be sent automatically to all
subscribed users.

__ http://reference.elgg.org/notification_8php.html#af7a43dcb0cf13ba55567d9d7874a3b20

Example
-------

Tell Elgg to send notifications when a new object of subtype "photo" is created:

.. code-block:: php

	/**
	 * Initialize the photos plugin
	 */
	function photos_init() {
		elgg_register_notification_event('object', 'photo', array('create'));
	}

.. note::

	In order to send the event-based notifications you must have the one-minute
	:doc:`CRON </admin/cron>` interval configured.

Contents of the notification message can be defined with the
``'prepare', 'notification:[action]:[type]:[subtype]'`` hook.

Example
-------

Tell Elgg to use the function ``photos_prepare_notification()`` to format
the contents of the notification when a new objects of subtype 'photo' is created:

.. code-block:: php

	/**
	 * Initialize the photos plugin
	 */
	function photos_init() {
	    elgg_register_notification_event('object', 'photo', array('create'));
	    elgg_register_plugin_hook_handler('prepare', 'notification:create:object:photo', 'photos_prepare_notification');
	}

	/**
	 * Prepare a notification message about a new photo
	 *
	 * @param string                          $hook         Hook name
	 * @param string                          $type         Hook type
	 * @param Elgg_Notifications_Notification $notification The notification to prepare
	 * @param array                           $params       Hook parameters
	 * @return Elgg_Notifications_Notification
	 */
	function photos_prepare_notification($hook, $type, $notification, $params) {
	    $entity = $params['event']->getObject();
	    $owner = $params['event']->getActor();
	    $recipient = $params['recipient'];
	    $language = $params['language'];
	    $method = $params['method'];

	    // Title for the notification
	    $notification->subject = elgg_echo('photos:notify:subject', array($entity->title), $language);

	    // Message body for the notification
	    $notification->body = elgg_echo('photos:notify:body', array(
	        $owner->name,
	        $entity->title,
	        $entity->getExcerpt(),
	        $entity->getURL()
	    ), $language);

	    // Short summary about the notification
	    $notification->summary = elgg_echo('photos:notify:summary', array($entity->title), $language);

	    return $notification;
	}

.. note::

	Make sure the notification will be in the correct language by passing
	the reciepient's language into the ``elgg_echo()`` function.

Registering a new notification method
======================================

By default Elgg has two notification methods: email and the bundled
site_notifications plugin. You can register a new notification
method with the `elgg_register_notification_method()`__ function.

__ http://reference.elgg.org/notification_8php.html#ac9e7b5583afbb992b8222ae1db072dd1

Example:
--------

Register a handler that will send the notifications via SMS.

.. code-block:: php

	/**
	 * Initialize the plugin
	 */
	function sms_notifications_init () {
		elgg_register_notification_method('sms');
	}

After registering the new method, it will appear to the notification
settings page at ``www.example.com/notifications/personal/[username]``.

Sending the notifications using your own method
===============================================

Besides registering the notification method, you also need to register
a handler that takes care of actually sending the SMS notifications.
This happens with the ``'send', 'notification:[method]'`` hook.

Example:
--------

.. code-block:: php

	/**
	 * Initialize the plugin
	 */
	function sms_notifications_init () {
		elgg_register_notification_method('sms');
		elgg_register_plugin_hook_handler('send', 'notification:sms', 'sms_notifications_send');
	}

	/**
	 * Send an SMS notification
	 * 
	 * @param string $hook   Hook name
	 * @param string $type   Hook type
	 * @param bool   $result Has anyone sent a message yet?
	 * @param array  $params Hook parameters
	 * @return bool
	 * @access private
	 */
	function sms_notifications_send($hook, $type, $result, $params) {
		/* @var Elgg_Notifications_Notification $message */
		$message = $params['notification'];

		$recipient = $message->getRecipient();

		if (!$recipient || !$recipient->mobile) {
			return false;
		}

		// (A pseudo SMS API class) 
		$sms = new SmsApi();

		return $sms->send($recipient->mobile, $message->body);
	}

Subscriptions
=============

In most cases Elgg core takes care of handling the subscriptions,
so notification plugins don't usually have to alter them.

Subscriptions can however be:
 - Added using the `elgg_add_subscription()`__ function
 - Removed using the `elgg_remove_subscription()`__ function

__ http://reference.elgg.org/notification_8php.html#ab793c2e2a7027cfe3a1db3395f85917b
__ http://reference.elgg.org/notification_8php.html#a619fcbadea86921f7a19fb09a6319de7

It's possible to modify the recipients of a notification dynamically
with the ``'get', 'subscriptions'`` hook.

Example:
--------

.. code-block:: php

	/**
	 * Initialize the plugin
	 */
	function discussion_init() {
		elgg_register_plugin_hook_handler('get', 'subscriptions', 'discussion_get_subscriptions');
	}

	/**
	 * Get subscriptions for group notifications
	 *
	 * @param string $hook          'get'
	 * @param string $type          'subscriptions'
	 * @param array  $subscriptions Array containing subscriptions in the form
	 *                       <user guid> => array('email', 'site', etc.)
	 * @param array  $params        Hook parameters
	 * @return array
	 */
	function discussion_get_subscriptions($hook, $type, $subscriptions, $params) {
		$reply = $params['event']->getObject();

		if (!elgg_instanceof($reply, 'object', 'discussion_reply', 'ElggDiscussionReply')) {
			return $subscriptions;
		}

		$group_guid = $reply->getContainerEntity()->container_guid;
		$group_subscribers = elgg_get_subscriptions_for_container($group_guid);

		return ($subscriptions + $group_subscribers);
	}
