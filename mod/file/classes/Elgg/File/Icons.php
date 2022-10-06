<?php

namespace Elgg\File;

/**
 * Event callbacks for icons
 *
 * @since 4.0
 *
 * @internal
 */
class Icons {
	
	/**
	 * Set custom icon sizes for file objects
	 *
	 * @param \Elgg\Event $event "entity:icon:sizes", "object"
	 *
	 * @return array
	 */
	public static function setIconSizes(\Elgg\Event $event) {
	
		if ($event->getParam('entity_subtype') !== 'file') {
			return;
		}
	
		$return = $event->getValue();

		$return['xlarge'] = [
			'w' => 600,
			'h' => 600,
			'upscale' => false,
		];
		
		return $return;
	}
}
