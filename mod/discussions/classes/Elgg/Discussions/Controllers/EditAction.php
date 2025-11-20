<?php

namespace Elgg\Discussions\Controllers;

use Elgg\Exceptions\Http\ValidationException;

/**
 * Topic save action
 *
 * @property \ElggDiscussion $entity the discussion entity
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
	
	/**
	 * {@inheritdoc}
	 */
	protected function execute(array $skip_field_names = []): void {
		$skip_field_names[] = 'status';
		
		parent::execute($skip_field_names);
		
		$status = $this->request->getParam('status');
		if ($status === null) {
			return;
		}
		
		// check if status is still a supported field
		$status_found = false;
		$fields = elgg()->fields->get('object', 'discussion');
		foreach ($fields as $field) {
			if (elgg_extract('name', $field) !== 'status') {
				continue;
			}
			
			$status_found = true;
			break;
		}
		
		if (!$status_found) {
			return;
		}
		
		try {
			$this->entity->setStatus($status);
		} catch (\Elgg\Exceptions\ExceptionInterface $e) {
			throw new ValidationException(elgg_echo('discussion:error:status'), 0, $e);
		}
	}
}
