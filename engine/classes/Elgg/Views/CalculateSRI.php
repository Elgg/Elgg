<?php

namespace Elgg\Views;

/**
 * Calculates SRI for simplecache resources
 *
 * @since 4.3
 */
class CalculateSRI {
	
	/**
	 * Calculates the SRI of a simplecache resource file for future use
	 *
	 * @param \Elgg\Event $event 'simplecache:generate', '[css|js]'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Event $event) {
		$view = $event->getParam('view');
		if (empty($view)) {
			return;
		}
		
		$type = $event->getType();
		
		$data = _elgg_services()->serverCache->load('sri') ?? [];
		if (!isset($data[$type])) {
			$data[$type] = [];
		}
		
		if (isset($data[$type][$view])) {
			return;
		}
		
		$hash = base64_encode(hash('sha256', $event->getValue(), true));
		$data[$type][$view] = "sha256-{$hash}";
		_elgg_services()->serverCache->save('sri', $data);
	}
}
