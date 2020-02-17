<?php
/**
 * Elgg Groups.
 * Groups contain other entities, or rather act as a placeholder for other entities to
 * mark any given container as their container.
 */

/**
 * Allow group members to write to the group container
 *
 * @param \Elgg\Hook $hook 'container_permissions_check', 'all'
 *
 * @return bool
 * @internal
 */
function _elgg_groups_container_override(\Elgg\Hook $hook) {
	$container = $hook->getParam('container');
	$user = $hook->getUserParam();

	if ($container instanceof ElggGroup && $user) {
		if ($container->isMember($user)) {
			return true;
		}
	}
}

/**
 * Don't allow users to comment on content in a group they aren't a member of
 *
 * @param \Elgg\Hook $hook 'permissions_check:comment', 'object'
 *
 * @return void|false
 * @internal
 * @since 3.1
 */
function _elgg_groups_comment_permissions_override(\Elgg\Hook $hook) {
	
	if ($hook->getValue() === false) {
		// already not allowed, no need to check further
		return;
	}
	
	$entity = $hook->getEntityParam();
	$user = $hook->getUserParam();
	
	if (!$entity instanceof ElggObject || !$user instanceof ElggUser) {
		return;
	}
	
	$container = $entity->getContainerEntity();
	if (!$container instanceof ElggGroup) {
		return;
	}
	
	if ($container->isMember($user)) {
		return;
	}
	
	return false;
}
