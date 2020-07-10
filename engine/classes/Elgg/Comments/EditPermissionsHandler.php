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
		
		if (!$entity instanceof \ElggComment || !$user instanceof \ElggUser) {
			return;
		}
		
		$return = function () use ($entity, $user) {
			return $entity->owner_guid === $user->guid;
		};
		
		$content = $entity->getContainerEntity();
		if (!$content instanceof \ElggEntity) {
			return $return();
		}
		
		$container = $content->getContainerEntity();
		
		// use default access for group editors to moderate comments
		if ($container instanceof \ElggGroup && $container->canEdit($user->guid)) {
			return;
		}
		
		return $return();
	}
}
