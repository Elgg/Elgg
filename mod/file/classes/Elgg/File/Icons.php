<?php

namespace Elgg\File;

/**
 * Hook callbacks for icons
 *
 * @since 4.0
 *
 * @internal
 */
class Icons {
	
	/**
	 * Set custom icon sizes for file objects
	 *
	 * @param \Elgg\Hook $hook "entity:icon:sizes", "object"
	 *
	 * @return array
	 */
	public static function setIconSizes(\Elgg\Hook $hook) {
	
		if ($hook->getParam('entity_subtype') !== 'file') {
			return;
		}
	
		$return = $hook->getValue();

		$return['xlarge'] = [
			'w' => 600,
			'h' => 600,
			'upscale' => false,
		];
		
		return $return;
	}
}
