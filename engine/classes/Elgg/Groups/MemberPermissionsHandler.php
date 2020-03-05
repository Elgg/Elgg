<?php

namespace Elgg\Groups;

/**
 * Check if content can be created in a group based on group membership of a user
 *
 * @since 4.0
 */
class MemberPermissionsHandler {
	
	/**
	 * Allow group members to write to the group container
	 *
	 * @param \Elgg\Hook $hook 'container_permissions_check', 'all'
	 *
	 * @return void|true
	 */
	public function __invoke(\Elgg\Hook $hook) {
		
		$container = $hook->getParam('container');
		if (!$container instanceof \ElggGroup) {
			return;
		}
		
		$user = $hook->getUserParam();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		if ($container->isMember($user)) {
			return true;
		}
	}
}
