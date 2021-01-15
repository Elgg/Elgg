<?php

namespace Elgg\Icons;

/**
 * Touches icons on access changes
 *
 * @since 4.0
 */
class TouchIconsOnAccessChangeHandler {
	
	/**
	 * Reset icon URLs if access_id has changed
	 *
	 * @param \Elgg\Event $event 'update:after', 'object|group'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event) {
		$entity = $event->getObject();
		if (!$entity instanceof \ElggEntity) {
			return;
		}
		
		$original_attributes = $entity->getOriginalAttributes();
		if (!array_key_exists('access_id', $original_attributes)) {
			return;
		}
		
		if ($entity instanceof \ElggFile) {
			// we touch the file to invalidate any previously generated download URLs
			$entity->setModifiedTime();
		}
		
		$sizes = array_keys(elgg_get_icon_sizes($entity->getType(), $entity->getSubtype()));
		foreach ($sizes as $size) {
			// using the Icon Service because we don't want to auto generate the 'new' icon
			$icon = _elgg_services()->iconService->getIcon($entity, $size, 'icon', false);
			if (!$icon->exists()) {
				continue;
			}
			
			$icon->setModifiedTime();
		}
	}
}
