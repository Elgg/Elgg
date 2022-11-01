<?php

namespace Elgg\Comments;

/**
 * Check if commenting on group content is allowed
 *
 * @since 4.0
 */
class GroupMemberPermissionsHandler {
	
	/**
	 * Don't allow users to comment on content in a group they aren't a member of
	 *
	 * @param \Elgg\Event $event 'permissions_check:comment', 'object'
	 *
	 * @return void|false
	 */
	public function __invoke(\Elgg\Event $event) {
		
		if ($event->getValue() === false) {
			// already not allowed, no need to check further
			return;
		}
		
		if (!elgg_get_config('comments_group_only')) {
			return;
		}
		
		$entity = $event->getEntityParam();
		$user = $event->getUserParam();
		
		if (!$entity instanceof \ElggObject || !$user instanceof \ElggUser) {
			return;
		}
		
		$container = $entity->getContainerEntity();
		if (!$container instanceof \ElggGroup) {
			return;
		}
		
		if ($container->isMember($user)) {
			return;
		}
		
		return false;
	}
}
