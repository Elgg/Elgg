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
	 * @param \Elgg\Event $event 'prepare', 'menu:breadcrumbs'
	 *
	 * @return void|PreparedMenu
	 */
	public static function cleanupBreadcrumbs(\Elgg\Event $event) {
		/* @var $breadcrumbs PreparedMenu */
		$breadcrumbs = $event->getValue();
		
		$items = $breadcrumbs->getItems('default');
		if (empty($items)) {
			return;
		}
		
		$last = null;
		foreach ($items as $crumb) {
			$last = $crumb;
			$crumb->setText(elgg_get_excerpt((string) $crumb->getText(), 100));
		}
		
		// remove last crumb if it has no link
		if (empty($last->getHref())) {
			$breadcrumbs->getSection('default')->remove($last->getID());
		}
	}
}
