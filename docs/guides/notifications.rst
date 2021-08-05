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
	$summary = elgg_echo('ratings:notification:summary', array($user->getDisplayName()), $owner->language);

	// Body of the notification message
	$body = elgg_echo('ratings:notification:body', array(
		$user->getDisplayName(),
		$owner->getDisplayName(),
		$rating->getValue() // A value between 1-5
	), $owner->language);

	$params = array(
		'object' => $rating,
		'action' => 'create',
		'summary' => $summary
	);

	// Send the notification
	notify_user($owner->guid, $user->guid, $subject, $body, $params);

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

New notification events can be registered with the ``elgg_register_notification_event()``
function or in the :doc:`elgg-plugin </guides/plugins>` configuration. Notifications about registered events will be sent automatically to all
subscribed users.

This is the workflow of the notifications system:

 #. Someone does an action that triggers an event within Elgg
     - The action can be ``create``, ``update`` or ``delete``
     - The target of the action can be any instance of the ``ElggEntity`` class (e.g. a Blog post)
 #. The notifications system saves this event into a notifications queue in the database
 #. When the pluging hook handler for the one-minute interval gets triggered, the event is taken from the queue and it gets processed
 #. Subscriptions are fetched for the user who triggered the event
     - By default this includes all the users who have enabled any notification method
       for the user at ``www.site.com/notifications/personal/<username>``
 #. Plugins are allowed to alter the subscriptions using the ``[get, subscriptions]`` hook
 #. Plugins are allowed to terminate notifications queue processing with the ``[send:before, notifications]`` hook
 #. Plugins are allowed to alter the notification parameters with the ``[prepare, notification]`` hook
 #. Plugins are allowed to alter the notification subject/message/summary with the ``[prepare, notification:<action>:<type>:<subtype>]`` hook
 #. Plugins are allowed to format notification subject/message/summary for individual delivery methods with ``[format, notification:<method>]`` hook
 #. Notifications are sent to each subscriber using the methods they have chosen
     - Plugins can take over or prevent sending of each individual notification with the ``[send, notification:<method>]`` hook
 #. The ``[send:after, notifications]`` hook is triggered for the event after all notifications have been sent

Notification event registration example
---------------------------------------

Tell Elgg to send notifications when a new object of subtype "photo" is created:

.. code-block:: php

	/**
	 * Initialize the photos plugin
	 */
	function photos_init() {
		elgg_register_notification_event('object', 'photo', array('create'));
	}

Or in the ``elgg-plugin.php``:

.. code-block:: php

	'notifications' => [
		'object' => [
			'photo' => [
				'create' => true,
			],
		],
	],

.. note::

	In order to send the event-based notifications you must have the one-minute
	:doc:`CRON </admin/cron>` interval configured.

Contents of the notification message can be defined with the
``'prepare', 'notification:[action]:[type]:[subtype]'`` hook.


Custom notification event registration example
----------------------------------------------

Tell Elgg to send notifications when a new object of the subtype "album" is created:

.. code-block:: php

	// in the elgg-plugin.php
	'notifications' => [
		'object' => [
			'photo' => [
				'create' => PhotoAlbumCreateNotificationHandler::class, // this needs to be an extension of the \Elgg\Notifications\NotificationEventHandler class
			],
		],
	],
	
	//PhotoAlbumCreateNotificationHandler.php
	
	class PhotoAlbumCreateNotificationHandler extends \Elgg\Notifications\NotificationEventHandler {
		
		/**
		 * Overrule this function if you wish to modify the subscribers of this notification
		 *
		 * This will influence which subscribers are available in the 'get', 'subscribers' hook
		 */
		public function getSubscriptions(): array {
		}
		
		/**
		 * Overrule this function if you wish to modify the subject of the notification
		 * 
		 * A magic language key is checked for a default notification:
		 * 'notification:<action>:<type>:<subtype>:subject'
		 */
		protected function getNotificationSubject(\ElggUser $recipient, string $method): string {
		}
		
		/**
		 * Overrule this function if you wish to modify the body of the notification
		 *
		 * A magic language key is checked for a default notification:
		 * 'notification:<action>:<type>:<subtype>:body'
		 */
		protected function getNotificationBody(\ElggUser $recipient, string $method): string {
		}
		
		/**
		 * Overrule this function if you wish to modify the summary of the notification
		 *
		 * default: ''
		 */
		protected function getNotificationSummary(\ElggUser $recipient, string $method): string {
		}
		
		/**
		 * Overrule this function if you wish to modify the target url of the notification
		 * 
		 * default: $event->object->getURL()
		 */
		protected function getNotificationURL(\ElggUser $recipient, string $method): string {
		}
		
		/**
		 * Overrule this function if you don't wish to allow the notification event to be configurable on the user notification settings page
		 * 
		 * default: true
		 */
		public static function isConfigurableByUser(): bool {
		}
	}

.. note::

	Make sure the notification will be in the correct language by passing
	the reciepient's language into the ``elgg_echo()`` function.

Custom notification content example
-----------------------------------

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
	 * @param \Elgg\Hook $hook 'prepare', 'notification:create:object:photo'
	 
	 * @return \Elgg\Notification\Notification
	 */
	function photos_prepare_notification(\Elgg\Hook $hook) {
	    $event = $hook->getParam('event');
	    
	    $entity = $event->getObject();
	    $owner = $event->getActor();
	    $recipient = $hook->getParam('recipient');
	    $language = $hook->getParam('language');
	    $method = $hook->getParam('method');

	    /* @var $notification \Elgg\Notification\Notification */
	    $notification = $hook->getValue();
	    
	    // Title for the notification
	    $notification->subject = elgg_echo('photos:notify:subject', [$entity->getDisplayName()], $language);

	    // Message body for the notification
	    $notification->body = elgg_echo('photos:notify:body', array(
	        $owner->getDisplayName(),
	        $entity->getDisplayName(),
	        $entity->getExcerpt(),
	        $entity->getURL()
	    ), $language);

	    // Short summary about the notification
	    $notification->summary = elgg_echo('photos:notify:summary', [$entity->getDisplayName()], $language);

	    return $notification;
	}

.. note::

	Make sure the notification will be in the correct language by passing
	the reciepient's language into the ``elgg_echo()`` function.
	
Notification salutation and sign-off
====================================

Elgg will by default prepend a salutation to all outgoing notification body text. Also a sign-off will be appended.
This means you will not need to add text like ``Hi Admin,`` or ``Kind regards, your friendly site administrator`` to your notifications body.
If for some reason you do not need this magic to happen, you can prevent it by setting the notification parameter ``add_salutation`` to ``false``.
You can do this as part of the parameters in ``notify_user()`` or in the ``prepare, notifications`` hook. 
You can change the salutation and sign-off texts in the translations.

You can also customize the salutation by overruling the view ``notifications/elements/salutation`` the sign-off can be customized by overruling the view
``notifications/elements/sign-off``.

Notification methods
====================

By default Elgg has three notification methods: email, delayed_email and the bundled site_notifications plugin.

Email
-----

Will send an email notification to to the recipient.

Delayed email
-------------

Will save the notifications and deliver them in one bundled email at the interval the recipient has configured (daily or weekly).

The availability of this delivery method can be configured by the site administrator in the Site settings section.

The layout of the bundled email can be customized by overruling the view ``email/delayed_email/plain_text`` for the plain text part of the email and 
``email/delayed_email/html`` for the HTML part of the email.

Site notification
-----------------

Will show the notification on the site.

Registering a new notification method
======================================

You can register a new notification method with the ``elgg_register_notification_method()`` function.

Example:
--------

Register a handler that will send the notifications via SMS.

.. code-block:: php

	/**
	 * Initialize the plugin
	 */
	function sms_notifications_init() {
		elgg_register_notification_method('sms');
	}

After registering the new method, it will appear on the notification
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
	 * @param \Elgg\Hook $hook 'send', 'notification:sms'
	 *
	 * @return bool
	 * @internal
	 */
	function sms_notifications_send(\Elgg\Hook $hook) {
		/* @var \Elgg\Notifications\Notification $message */
		$message = $hook->getParam('notification');

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

In most cases Elgg core takes care of handling the subscriptions, so notification plugins don't usually have to alter them.

Subscriptions can however be:
 - Added using the ``\ElggEntity::addSubscription()`` function
 - Removed using the ``\ElggEntity::removeSubscription()`` function

It's possible to modify the recipients of a notification dynamically with the ``'get', 'subscriptions'`` hook.

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
	 * @param \Elgg\Hook $hook 'get', 'subscriptions'
	 *
	 * @return void|array
	 */
	function discussion_get_subscriptions(\Elgg\Hook $hook) {
		$reply = $hook->getParam('event')->getObject();

		if (!$reply instanceof \ElggDiscussionReply) {
			return;
		}

		$subscriptions = $hook->getValue();
		
		$group_guid = $reply->getContainerEntity()->container_guid;
		$group_subscribers = elgg_get_subscriptions_for_container($group_guid);

		return ($subscriptions + $group_subscribers);
	}

Muted notifications
===================

Notifications can be muted in order to no longer receive notifications, for example no longer receive notifications about new comments on a discussion.

In order to mute notifications call ``\ElggEntity::muteNotifications($user_guid)`` the ``$user_guid`` is defaulted to the current logged in user.
This will cause all subscriptions on the entity to be removed and a special flag will be set to know that notifications are muted.

The muting rules are applied after the subscribers of a notification event are requested and are applied for the following entities of the notification event:
- the event actor ``\Elgg\Notifications\NotificationEvent::getActor()``
- the event object entity ``\Elgg\Notifications\NotificationEvent::getObject()``
- the event object container entity ``\Elgg\Notifications\NotificationEvent::getObject()::getContainerEntity()``
- the event object owner entity ``\Elgg\Notifications\NotificationEvent::getObject()::getOwnerEntity()``

To unmute the notifications call ``\ElggEntity::unmuteNotifications($user_guid)`` the ``$user_guid`` is defaulted to the current logged in user.

To check if a user has the notifications muted call ``\ElggEntity::hasMutedNotifications($user_guid)`` the ``$user_guid`` is defaulted to the current logged in user.

Helper page
-----------

A helper page has been added which can be linked (for example in an email footer) to manage muting based on a notification.

The page is required to be signed and use the route ``notifications:mute`` which needs:
- ``entity_guid`` the entity the notification is about
- ``recipient_guid`` the recipient of the notification

Temporarily disable notifications
=================================

Users can temporarily disable all notifications by going to the Notification settings and set a start and end date for the period they don't wish to receive any notifications.

Notification settings
=====================

You can store and retreive notification settings of users with ``\ElggUser::setNotificationSetting()`` and ``\ElggUser::getNotificationSettings()``.

.. code-block:: php

	// Setting a notification preference
	// notification method: mail
	// notification is enabled
	// for the purpose 'group_join' (when omitted this is 'default')
	$user->setNotificationSetting('mail', true, 'group_join');
	
	// retrieving the preference
	$settings = $user->getNotificationSettings('group_join');
	// this wil result in an array with all the current notification methods and their state like:
	// [
	//	'mail' => true,
	//	'site' => false,
	//	'sms' => false,
	// ]

When a user has no setting yet for a non default purpose the system will fallback to the 'default' notification setting.

Notification management
=======================

A generic menu hook handler is provided to manage notification subscription and muting. If you wish to make it easy for users to subscribe to 
your entities register a menu hook on ``register`` ``menu:<menu name>:<entity type>:<entity subtype>`` with the callback 
``Elgg\Notifications\RegisterSubscriptionMenuItemsHandler`` make sure an ``\ElggEntity`` in ``$params['entity']`` is provided. 
This will work for most ``elgg_view_menu()`` calls.
