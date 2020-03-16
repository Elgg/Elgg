<?php

namespace Elgg\Database;

/**
 * Registers seeds
 *
 * @since 4.0
 */
class RegisterSeedsHandler {
	
	/**
	 * Register database seeds
	 *
	 * @param \Elgg\Hook $hook 'seeds', 'database'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Hook $hook) {
		$seeds = $hook->getValue();
	
		$seeds[] = \Elgg\Database\Seeds\Users::class;
		$seeds[] = \Elgg\Database\Seeds\Groups::class;
		
		return $seeds;
	}
}
