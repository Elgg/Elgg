<?php

namespace Elgg\Comments;

/**
 * When a comment is created subscribe the owner to the container (original content) of the comment
 * if the user has't muted the container yet
 *
 * @since 4.0
 * @internal
 */
class AutoSubscribeHandler {
	
	/**
	 * Subscribe the user to the comment container
	 *
	 * @param \Elgg\Event $event 'create', 'object'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event): void {
		$object = $event->getObject();
		if (!$object instanceof \ElggComment) {
			return;
		}
		
		$owner = $object->getOwnerEntity();
		$container = $object->getContainerEntity();
		if (!$owner instanceof \ElggUser || !$container instanceof \ElggEntity) {
			return;
		}
		
		if ($container->hasMutedNotifications($owner->guid)) {
			// user already said to not receive notifications, so don't force it
			return;
		}
		
		$comment_preferences = $owner->getNotificationSettings('create_comment');
		$enabled_methods = array_keys(array_filter($comment_preferences));
		if (empty($enabled_methods)) {
			return;
		}
		
		$container->addSubscription($owner->guid, $enabled_methods);
	}
}
