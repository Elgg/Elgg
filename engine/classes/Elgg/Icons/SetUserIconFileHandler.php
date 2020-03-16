<?php

namespace Elgg\Icons;

/**
 * Sets user icon file
 *
 * @since 4.0
 */
class SetUserIconFileHandler {
	
	/**
	 * Set user icon file
	 *
	 * @param \Elgg\Hook $hook 'entity:icon:file', 'user'
	 *
	 * @return \ElggIcon
	 */
	public function __invoke(\Elgg\Hook $hook) {
		$icon = $hook->getValue();
	
		$entity = $hook->getEntityParam();
		$size = $hook->getParam('size', 'medium');
	
		$icon->owner_guid = $entity->guid;
		$icon->setFilename("profile/{$entity->guid}{$size}.jpg");
	
		return $icon;
	}
}
