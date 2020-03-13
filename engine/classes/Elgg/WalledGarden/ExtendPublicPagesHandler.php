<?php

namespace Elgg\WalledGarden;

/**
 * Extends public pages
 *
 * @since 4.0
 */
class ExtendPublicPagesHandler {
	
	/**
	 * Extend public pages
	 *
	 * @param \Elgg\Hook $hook 'public_pages', 'walled_garden'
	 *
	 * @return string[]
	 */
	public function __invoke(\Elgg\Hook $hook) {
		$return_value = $hook->getValue();
	
		$return_value[] = 'navigation/menu/user_hover/contents';
		
		return $return_value;
	}
}
