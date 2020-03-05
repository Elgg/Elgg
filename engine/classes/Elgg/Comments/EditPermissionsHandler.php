<?php

namespace Elgg\Comments;

/**
 * Returns the correct behaviour for editing comments
 *
 * @since 4.0
 */
class EditPermissionsHandler {
	
	/**
	 * This makes sure only authors can edit their comments.
	 *
	 * @param \Elgg\Hook $hook 'permissions_check', 'object'
	 *
	 * @return void|boolean Whether the given user is allowed to edit the given comment.
	 */
	public function __invoke(\Elgg\Hook $hook) {
		$entity = $hook->getEntityParam();
		$user = $hook->getUserParam();
		
		if ($entity instanceof \ElggComment && $user instanceof \ElggUser) {
			return $entity->getOwnerGUID() === $user->guid;
		}
	}
}
