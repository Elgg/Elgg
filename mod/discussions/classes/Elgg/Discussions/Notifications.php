<?php

namespace Elgg\Discussions;

/**
 * Hook callbacks for notifications
 *
 * @since 4.0
 *
 * @internal
 */
class Notifications {
	
	/**
	 * Prepare a notification message about a new comment on a discussion
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'notification:create:object:comment'
	 *
	 * @return void|\Elgg\Notifications\Notification
	 */
	public static function prepareCommentOnDiscussionNotification(\Elgg\Hook $hook) {
		
		$event = $hook->getParam('event');
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
		
		$language = $hook->getParam('language');
		
		$poster = $comment->getOwnerEntity();
		
		$notification = $hook->getValue();
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
	 * @param \Elgg\Hook $hook 'get', 'subscriptions'
	 *
	 * @return void|array
	 */
	public static function addGroupSubscribersToCommentOnDiscussionSubscriptions(\Elgg\Hook $hook) {
		
		$event = $hook->getParam('event');
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
		
		$subscriptions = $hook->getValue();
		$group_subscriptions = elgg_get_subscriptions_for_container($container->guid);
		
		return ($subscriptions + $group_subscriptions);
	}
}
