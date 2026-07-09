<?php

namespace Elgg\TheWire\Controllers;

use Elgg\Controllers\GenericEntity;

/**
 * View/add/edit wire posts
 *
 * @since 7.1
 */
class Entity extends GenericEntity {
	
	/**
	 * {@inheritdoc}
	 */
	protected function getPageOptions(string $page, array $options): array {
		$options = parent::getPageOptions($page, $options);
		
		switch ($page) {
			case 'view':
				$entity = $this->getEntity();
				$options['title'] = elgg_echo('thewire:by', [$entity->getOwnerEntity()->getDisplayName()]);
				break;
		}
		
		return $options;
	}
}
