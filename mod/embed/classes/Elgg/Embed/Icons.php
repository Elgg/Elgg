<?php

namespace Elgg\Embed;

/**
 * Plugin hook handlers for icons
 */
class Icons {

	/**
	 * Substitutes thumbnail's inline URL with a permanent URL
	 * Registered with a very late priority of 1000 to ensure we replace all previous values
	 *
	 * @param \Elgg\Hook $hook "entity:icon:url", "object"
	 *
	 * @return string
	 */
	public static function setThumbnailUrl(\Elgg\Hook $hook) {
	
		if (!elgg_in_context('embed')) {
			return;
		}
		
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggEntity) {
			return;
		}
		
		$size = $hook->getParam('size');
	
		$thumbnail = $entity->getIcon($size);
		if (!$thumbnail->exists()) {
			return;
		}
	
		return elgg_get_embed_url($entity, $size);
	}
}
