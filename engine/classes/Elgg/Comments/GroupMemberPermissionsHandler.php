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
	 * @param \Elgg\Hook $hook 'permissions_check:comment', 'object'
	 *
	 * @return void|false
	 */
	public function __invoke(\Elgg\Hook $hook) {
		
		if ($hook->getValue() === false) {
			// already not allowed, no need to check further
			return;
		}
		
		$entity = $hook->getEntityParam();
		$user = $hook->getUserParam();
		
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
