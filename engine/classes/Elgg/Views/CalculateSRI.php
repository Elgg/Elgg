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
	 * @param \Elgg\Hook $hook 'simplecache:generate', '[css|js]'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Hook $hook) {
		$view = $hook->getParam('view');
		if (empty($view)) {
			return;
		}
		
		$type = $hook->getType();
		
		$data = _elgg_services()->serverCache->load('sri') ?? [];
		if (!isset($data[$type])) {
			$data[$type] = [];
		}
		
		if (isset($data[$type][$view])) {
			return;
		}
		
		$hash = base64_encode(hash('sha256', $hook->getValue(), true));
		$data[$type][$view] = "sha256-{$hash}";
		_elgg_services()->serverCache->save('sri', $data);
	}
}
