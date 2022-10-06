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
	 * @param \Elgg\Event $event 'entity:icon:file', 'user'
	 *
	 * @return \ElggIcon
	 */
	public function __invoke(\Elgg\Event $event) {
		$icon = $event->getValue();
	
		$entity = $event->getEntityParam();
		$size = $event->getParam('size', 'medium');
	
		$icon->owner_guid = $entity->guid;
		$icon->setFilename("profile/{$entity->guid}{$size}.jpg");
	
		return $icon;
	}
}
