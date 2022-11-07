<?php

namespace Elgg\Discussions;

/**
 * Event callbacks for notifications
 *
 * @since 4.0
 *
 * @internal
 */
class Notifications {
	
	/**
	 * Prepare a notification message about a new comment on a discussion
	 *
	 * @param \Elgg\Event $elgg_event 'prepare', 'notification:create:object:comment'
	 *
	 * @return void|\Elgg\Notifications\Notification
	 */
	public static function prepareCommentOnDiscussionNotification(\Elgg\Event $elgg_event) {
		
		$event = $elgg_event->getParam('event');
		if (!$event instanceof \Elgg\Notifications\NotificationEvent) {
			return;
		}
		
		$comment = $event->getObject();
		if (!$comment instanceof \ElggComment) {
			return;
		}
		
		$discussion = $comment->getContainerEntity();
		if (!$discussion instanceof \ElggDiscussion) {
			return;
		}
		
		$language = $elgg_event->getParam('language');
		
		$poster = $comment->getOwnerEntity();
		
		$notification = $elgg_event->getValue();
		$notification->subject = elgg_echo('discussion:comment:notify:subject', [$discussion->getDisplayName()], $language);
		$notification->summary = elgg_echo('discussion:comment:notify:summary', [$discussion->getDisplayName()], $language);
		$notification->body = elgg_echo('discussion:comment:notify:body', [
			$poster->getDisplayName(),
			$discussion->getDisplayName(),
			$comment->description,
			$comment->getURL(),
		], $language);
		$notification->url = $comment->getURL();
		
		return $notification;
	}
	
	/**
	 * Add group members to the comment subscriber on a discussion
	 *
	 * @param \Elgg\Event $elgg_event 'get', 'subscriptions'
	 *
	 * @return void|array
	 */
	public static function addGroupSubscribersToCommentOnDiscussionSubscriptions(\Elgg\Event $elgg_event) {
		
		$event = $elgg_event->getParam('event');
		if (!$event instanceof \Elgg\Notifications\SubscriptionNotificationEvent) {
			return;
		}
		
		if ($event->getAction() !== 'create') {
			return;
		}
		
		$comment = $event->getObject();
		if (!$comment instanceof \ElggComment) {
			return;
		}
		
		$discussion = $comment->getContainerEntity();
		if (!$discussion instanceof \ElggDiscussion) {
			return;
		}
		
		$container = $discussion->getContainerEntity();
		if (!$container instanceof \ElggGroup) {
			return;
		}
		
		if (!(bool) $container->getPluginSetting('discussions', 'add_group_subscribers_to_discussion_comments', true)) {
			return;
		}
		
		$subscriptions = $elgg_event->getValue();
		
		// get subscribers on the group (using the discussion create preference for detailed subscriptions)
		$methods = elgg_get_notification_methods();
		$group_subscriptions = _elgg_services()->subscriptions->getSubscriptionsForContainer($container->guid, $methods, 'object', 'discussion', 'create', $event->getActorGUID());
		
		return ($subscriptions + $group_subscriptions);
	}
}
