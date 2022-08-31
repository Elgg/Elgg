<?php

namespace Elgg\Menus;

use Elgg\Menu\PreparedMenu;

/**
 * Prepares breadcrumbs
 *
 * @since 5.0
 */
class Breadcrumbs {
	
	/**
	 * Prepare breadcrumbs before display. This turns titles into 100-character excerpts, and also
	 * removes the last crumb if it's not a link.
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'menu:breadcrumbs'
	 *
	 * @return void|PreparedMenu
	 */
	public static function cleanupBreadcrumbs(\Elgg\Hook $hook) {
		/** @var $breadcrumbs PreparedMenu */
		$breadcrumbs = $hook->getValue();
		
		$items = $breadcrumbs->getItems('default');
		if (empty($items)) {
			return;
		}
		
		$last = null;
		/** @var $crumb \ElggMenuItem */
		foreach ($items as $crumb) {
			$last = $crumb;
			$crumb->setText(elgg_get_excerpt($crumb->getText(), 100));
		}
		
		// remove last crumb if it has no link
		if (empty($last->getHref())) {
			$breadcrumbs->getSection('default')->remove($last->getID());
		}
	}
}
