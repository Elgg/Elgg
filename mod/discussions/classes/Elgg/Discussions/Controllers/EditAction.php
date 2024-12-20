<?php

namespace Elgg\Discussions\Controllers;

/**
 * Topic save action
 *
 * @since 6.2
 */
class EditAction extends \Elgg\Controllers\EntityEditAction {
	
	/**
	 * {@inheritdoc}
	 */
	protected function sanitize(): void {
		// access is null when a group is selected from the container_guid select
		$access_id = $this->request->getParam('access_id');
		if (isset($access_id)) {
			return;
		}
		
		$container = get_entity((int) $this->request->getParam('container_guid'));
		if (!$container instanceof \ElggGroup) {
			return;
		}
		
		$acl = $container->getOwnedAccessCollection('group_acl');
		if ($acl instanceof \ElggAccessCollection) {
			$this->request->setParam('access_id', $acl->getID());
		}
	}
}
