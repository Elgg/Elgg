<?php

namespace Elgg\Icons;

/**
 * Returns site icon sizes
 *
 * @since 4.1
 */
class GetSiteFaviconSizesHandler {
	
	/**
	 * Returns site icon sizes
	 *
	 * @param \Elgg\Event $event 'entity:favicon:sizes', 'site'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event) {
		$sizes = $event->getValue();
	
		$sizes['icon-16'] = [
			'w' => 16,
			'h' => 16,
			'square' => true,
			'upscale' => true,
			'crop' => true,
		];
	
		$sizes['icon-32'] = [
			'w' => 32,
			'h' => 32,
			'square' => true,
			'upscale' => true,
			'crop' => true,
		];
	
		$sizes['icon-64'] = [
			'w' => 64,
			'h' => 64,
			'square' => true,
			'upscale' => true,
			'crop' => true,
		];
	
		$sizes['icon-128'] = [
			'w' => 128,
			'h' => 128,
			'square' => true,
			'upscale' => true,
			'crop' => true,
		];
	
		return $sizes;
	}
}
