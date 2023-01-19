<?php

namespace Elgg\Icons;

/**
 * Returns header image sizes
 *
 * @since 5.0
 */
class HeaderSizesHandler {
	
	/**
	 * Returns header image sizes
	 *
	 * @param \Elgg\Event $event 'entity:header:sizes', 'all'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event) {
		$sizes = $event->getValue();
	
		$sizes['header'] = [
			'w' => 1920,
			'h' => 400,
			'square' => false,
			'upscale' => true,
			'crop' => true,
		];
	
		return $sizes;
	}
}
